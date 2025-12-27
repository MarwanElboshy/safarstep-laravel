function offerCreator() {
    return {
        currentStep: 0,
        autoSaving: false,
        aiLoading: false,
        aiPrompt: '',
        aiResult: null,
        showClientSearch: false,
        clientSearchQuery: '',
        clientSuggestions: [],
        showCreateCustomerModal: false,
        showAIModal: false,
        creatingClient: false,
        newClientForm: {
            name: '',
            phone: '',
            email: '',
            nationality: '',
            country: '',
            source: '',
            type: 'b2c',
            company_id: null,
            company_name: ''
        },
        
        // Company fields for B2B
        companySearch: '',
        companyResults: [],
        selectedCompany: null,
        showCompanyResults: false,
        showCompanyDropdown: false,
        showCreateCompany: false,
        
        steps: [
            { label: 'Basic Info', icon: 'info-circle' },
            { label: 'Cities', icon: 'map' },
            { label: 'Tours', icon: 'map-pin' },
            { label: 'Resources', icon: 'hotel' },
            { label: 'Pricing', icon: 'dollar' },
            { label: 'Details', icon: 'list' },
            { label: 'Review', icon: 'check' }
        ],

        form: {
            client_name: '',
            client_type: 'b2c',
            client_id: null,
            company_id: null,
            client_email: '',
            client_phone: '',
            client_nationality: '',
            client_country: '',
            client_source: '',
            department_id: '',
            start_date: '',
            end_date: '',
            duration_days: 0,
            travelers: {
                adults: 1,
                children: 0,
                infants: 0
            },
            primary_destination: '',
            offer_type: '',
            is_multi_city: false,
            internal_notes: '',
            cities: [],
            flight_outbound: null,
            internal_flights: [],
            flight_return: null,
            addons: [],
            inclusions: [],
            exclusions: []
        },

        departments: [],
        destinations: [],
        citySearch: '',
        citySearchResults: [],
        cachedCitiesForCountry: null, // Cache cities for current country
        cachedCountryName: null, // Track which country is cached
        cityColors: ['#2A50BC', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6', '#F97316'],
        activeCityId: null,
        calendarMode: 'single',
        rangeSelection: { start: null, end: null, selecting: false },
        hoveredDay: null,
        calendarMonth: null,
        calendarYear: null,
        dayAssignments: [],
        itinerary: [],
        itineraryUpdateTrigger: 0, // Force re-renders when changed
        dayLocations: [], // Array of {dayIndex, locations: [{place_id, name, address, lat, lng, types, city}]}
        dayTours: [], // Array of {dayIndex, tours: [{id, name, locations: [...], notes, duration, price}]}
        activeDayTab: 'locations', // 'locations' or 'tours' tab in Locations step
        createTourMode: false,
        showTourModal: false,
        tourModalDayIndex: null,
        newTourData: {
            name: '',
            duration: '',
            price: '',
            notes: '',
            locations: []
        },
        tourSearch: '',
        tourSearchResults: [],
        tourSearchLoading: false,
        availableTours: [], // Tours saved in this offer
        dragContext: {
            type: null, // 'day-location' | 'day-tour' | 'newtour-location'
            dayIndex: null,
            fromIndex: null
        },
        activeDay: null,
        locationSearch: '',
        locationSearchResults: [],
        locationSearchLoading: false,
        selectedLocation: null,
        locationPreviewModal: false,
        locationPreviewData: null,
        locationPreviewLoading: false,
        currentPhotoIndex: 0,
        showMapModal: false,
        activeResourceTab: 'accommodation',
        customInclusion: '',
        customExclusion: '',
        dayDetailsModal: false,
        dayDetailsIndex: null,

        pricing: {
            accommodation: { purchase: 0, sale: 0, profit: 0, margin: 0 },
            tours: { purchase: 0, sale: 0, profit: 0, margin: 0 },
            transport: { purchase: 0, sale: 0, profit: 0, margin: 0 },
            flights: { purchase: 0, sale: 0, profit: 0, margin: 0 },
            addons: { purchase: 0, sale: 0, profit: 0, margin: 0 }
        },

        get totalTravelers() {
            return this.form.travelers.adults + this.form.travelers.children + this.form.travelers.infants;
        },

        get totalPurchaseCost() {
            return Object.values(this.pricing).reduce((sum, cat) => sum + (cat.purchase || 0), 0);
        },

        get totalSalePrice() {
            return Object.values(this.pricing).reduce((sum, cat) => sum + (cat.sale || 0), 0);
        },

        get profit() {
            return this.totalSalePrice - this.totalPurchaseCost;
        },

        get profitMargin() {
            if (this.totalPurchaseCost === 0) return 0;
            return (this.profit / this.totalPurchaseCost) * 100;
        },

        // Unified days display: compute from dates when available
        get durationDays() {
            if (this.form.start_date && this.form.end_date) {
                const start = new Date(this.form.start_date);
                const end = new Date(this.form.end_date);
                const diffDays = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
                return diffDays > 0 ? diffDays : 0;
            }
            return this.form.duration_days || 0;
        },

        get totalNights() {
            if (this.form.start_date && this.form.end_date) {
                const start = new Date(this.form.start_date);
                const end = new Date(this.form.end_date);
                const diff = Math.floor((end - start) / (1000 * 60 * 60 * 24));
                return diff >= 0 ? diff : 0;
            }
            return Math.max(0, this.form.duration_days - 1);
        },

        get totalDistributedNights() {
            const assigned = this.dayAssignments.filter(d => d.cityId).length;
            if (assigned) return assigned;
            return this.form.cities.reduce((sum, city) => sum + (city.nights || 0), 0);
        },

        buildItinerary() {
            if (!this.form.start_date || this.totalNights <= 0) {
                console.log('⚠️ Itinerary: Missing data', {
                    start_date: this.form.start_date,
                    totalNights: this.totalNights
                });
                this.itinerary = [];
                return;
            }

            const previous = Array.isArray(this.itinerary) ? this.itinerary : [];
            const days = [];
            let currentDate = new Date(this.form.start_date);

            for (let i = 0; i < this.totalNights; i++) {
                const city = this.getCityForDay(i);
                const prev = previous[i] || {};
                const locations = this.getLocationsForDay(i) || [];

                days.push({
                    id: i,
                    date: new Date(currentDate),
                    dateString: currentDate.toISOString().split('T')[0],
                    dayNumber: i + 1,
                    city,
                    country: this.form.client_country || null,
                    isLastNight: i === this.totalNights - 1,
                    isCityChange: this.isCityChangeDay(i),
                    nextCity: this.getNextCity(i),
                    locations,
                    accommodation: prev.accommodation || null,
                    tours: prev.tours ? [...prev.tours] : [],
                    transport: prev.transport || null,
                    notes: prev.notes || ''
                });

                currentDate.setDate(currentDate.getDate() + 1);
            }

            this.itinerary = days;

            console.log('✅ Itinerary built:', {
                totalNights: this.totalNights,
                daysBuilt: this.itinerary.length,
                cities: this.itinerary.map(d => d.city)
            });
        },
        
        getLocationsForDay(dayIndex) {
            const dayLoc = this.dayLocations?.find(dl => dl.dayIndex === dayIndex);
            return dayLoc ? dayLoc.locations : [];
        },

        // Inclusions/Exclusions suggestions
        get inclusionSuggestions() {
            const suggestions = new Set();
            // Hotels
            if (this.itinerary.some(d => d.accommodation)) {
                suggestions.add('Accommodation in selected hotels');
                suggestions.add('Daily breakfast at hotel');
            }
            // Tours
            if (this.itinerary.some(d => d.tours && d.tours.length)) {
                suggestions.add('Guided tours as per itinerary');
                suggestions.add('Entrance fees to mentioned attractions');
            }
            // Transport
            if (this.itinerary.some(d => d.transport)) {
                suggestions.add('Private transfers between cities');
                suggestions.add('Airport pickup and drop-off');
            }
            // Flights
            if (this.form.flight_outbound || (this.form.internal_flights?.length) || this.form.flight_return) {
                suggestions.add('Domestic and international flight tickets');
                suggestions.add('Checked baggage allowance per airline policy');
            }
            // Add-ons
            if (this.form.addons?.length) {
                suggestions.add('SIM cards for connectivity');
                suggestions.add('Travel insurance');
            }
            return Array.from(suggestions);
        },
        get exclusionSuggestions() {
            const suggestions = new Set([
                'Personal expenses',
                'Meals not mentioned in the program',
                'Optional tours and activities',
                'Visa fees (unless specified)',
                'Tips and gratuities',
            ]);
            // If no flights selected, exclude flights
            if (!this.form.flight_outbound && !(this.form.internal_flights?.length) && !this.form.flight_return) {
                suggestions.add('International and domestic flights');
            }
            return Array.from(suggestions);
        },

        toggleInclusion(item) {
            const idx = this.form.inclusions.indexOf(item);
            if (idx >= 0) this.form.inclusions.splice(idx, 1);
            else this.form.inclusions.push(item);
        },
        toggleExclusion(item) {
            const idx = this.form.exclusions.indexOf(item);
            if (idx >= 0) this.form.exclusions.splice(idx, 1);
            else this.form.exclusions.push(item);
        },

        // Flatten itinerary into header + day rows for reliable rendering
        flattenedRows() {
            const rows = [];
            if (!Array.isArray(this.itinerary)) return rows;
            for (let idx = 0; idx < this.itinerary.length; idx++) {
                const day = this.itinerary[idx];
                if (idx === 0 || (this.itinerary[idx - 1]?.city !== day?.city)) {
                    rows.push({ type: 'header', city: day?.city || 'Unknown City', key: `h-${idx}-${day?.city || 'unknown'}` });
                }
                rows.push({ type: 'day', dayIndex: idx, day, key: `d-${idx}` });
            }
            return rows;
        },
        rowKey(row) {
            return row?.key || (row?.type === 'header' ? `h-${row?.city}` : `d-${row?.dayIndex}`);
        },
        // Group itinerary by consecutive city blocks for reliable table rendering
        groupedItinerary() {
            const groups = [];
            if (!Array.isArray(this.itinerary) || this.itinerary.length === 0) {
                return groups;
            }
            
            let currentCity = null;
            for (let i = 0; i < this.itinerary.length; i++) {
                const d = this.itinerary[i];
                const cityName = d?.city || 'Unknown City';
                if (cityName !== currentCity) {
                    groups.push({ city: cityName, days: [] });
                    currentCity = cityName;
                }
                // Push only dayIndex - template will reference itinerary[dayIndex] directly
                groups[groups.length - 1].days.push(i);
            }
            
            return groups;
        },
        selectAll(type) {
            if (type === 'inclusions') this.form.inclusions = [...this.inclusionSuggestions];
            if (type === 'exclusions') this.form.exclusions = [...this.exclusionSuggestions];
        },
        clearInclusionsExclusions() {
            this.form.inclusions = [];
            this.form.exclusions = [];
        },
        addCustom(type) {
            if (type === 'inclusion' && this.customInclusion?.trim()) {
                this.form.inclusions.push(this.customInclusion.trim());
                this.customInclusion = '';
            }
            if (type === 'exclusion' && this.customExclusion?.trim()) {
                this.form.exclusions.push(this.customExclusion.trim());
                this.customExclusion = '';
            }
        },
        applyStandardSet() {
            // Basic standard per destination
            this.selectAll('inclusions');
            this.selectAll('exclusions');
        },

        canSubmitOffer() {
            return this.canProceedFromStep1 &&
                   this.totalDistributedNights === this.totalNights &&
                   this.itinerary.length === this.form.duration_days &&
                   this.totalSalePrice >= this.totalPurchaseCost &&
                   this.form.inclusions.length > 0;
        },
        async submitOffer() {
            if (!this.canSubmitOffer()) return;

            const payload = {
                title: `Offer: ${this.form.primary_destination} (${this.form.duration_days} days)`,
                start_date: this.form.start_date,
                end_date: this.form.end_date,
                duration_days: this.form.duration_days,
                country_id: this.form.primary_destination,
                cities: this.form.cities.map(c => ({ name: c.name, nights: c.nights })),
                itinerary: this.itinerary.map(d => ({
                    date: d.date,
                    city: d.city,
                    accommodation: d.accommodation,
                    tours: d.tours,
                    transport: d.transport,
                    notes: d.notes
                })),
                pricing: this.pricing,
                price_per_person: this.totalTravelers > 0 ? (this.totalSalePrice / this.totalTravelers) : this.totalSalePrice,
                flights: {
                    outbound: this.form.flight_outbound,
                    internal: this.form.internal_flights,
                    return: this.form.flight_return
                },
                addons: this.form.addons,
                inclusions: this.form.inclusions,
                exclusions: this.form.exclusions,
                meta: {
                    offer_type: this.form.offer_type,
                    travelers: this.form.travelers,
                    client: { id: this.form.client_id, name: this.form.client_name, type: this.form.client_type },
                    department_id: this.form.department_id ? parseInt(this.form.department_id) : null,
                    notes: this.form.internal_notes
                }
            };

            try {
                const response = await fetch(`${window.appConfig?.apiUrl}/v1/offers`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    credentials: 'include',
                    body: JSON.stringify(payload)
                });
                const data = await response.json();
                if (data.success) {
                    alert('Offer saved successfully');
                    // Optionally redirect
                    // window.location.href = `${window.appConfig.baseUrl}/dashboard/offers/${data.data.id}`;
                } else {
                    alert('Failed to save offer: ' + (data.message || 'Unknown error'));
                }
            } catch (err) {
                console.error('Save offer error:', err);
                alert('An error occurred while saving the offer');
            }
        },

        get completionPercentage() {
            let completed = 0;
            const checks = [
                this.form.client_name,
                this.form.start_date && this.form.end_date,
                this.form.travelers.adults > 0,
                this.form.primary_destination,
                this.form.offer_type
            ];
            completed = checks.filter(Boolean).length;
            return Math.round((completed / checks.length) * 100);
        },

        get canProceedFromStep1() {
            const hasClient = this.form.client_name || this.form.client_id;
            const hasDates = this.form.start_date && this.form.end_date;
            const hasTravelers = this.form.travelers.adults > 0;
            const hasDestination = this.form.primary_destination;
            const hasOfferType = this.form.offer_type;
            
            console.log('Step 1 Validation:', {
                hasClient,
                hasDates,
                hasTravelers,
                hasDestination,
                hasOfferType,
                result: hasClient && hasDates && hasTravelers && hasDestination && hasOfferType
            });
            
            return hasClient && hasDates && hasTravelers && hasDestination && hasOfferType;
        },

        async init() {
            await this.loadDepartments();
            await this.loadDestinations();
        },

        async handlePrimaryDestinationChange() {
            console.log('Destination changed to:', this.form.primary_destination);
            // Reset city selections when destination changes to avoid mixing countries
            this.form.cities = [];
            this.activeCityId = null;
            this.citySearch = '';
            this.citySearchResults = [];
            this.cachedCitiesForCountry = null; // Clear cache when destination changes
            this.cachedCountryName = null;
            this.syncAssignments();
            await this.preloadCitiesForCountry();
        },

        async preloadCitiesForCountry() {
            const { countryName, countryCode } = this.getSelectedDestinationContext();
            console.log('Preload cities for country:', { countryName, countryCode });
            if (!countryName) return;
            
            // Check if we already have cached cities for this country
            if (this.cachedCountryName === countryName && this.cachedCitiesForCountry !== null) {
                console.log('Using cached cities:', this.cachedCitiesForCountry.length);
                this.citySearchResults = this.cachedCitiesForCountry;
                return;
            }
            
            try {
                // First try to load from database
                const response = await fetch(`${window.appConfig?.apiUrl}/v1/cities/by-country`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include',
                    body: JSON.stringify({ country_name: countryName })
                });
                
                const data = await response.json();
                console.log('Preload response:', { success: data.success, count: data.data?.length, status: response.status });
                if (!data.success) {
                    console.warn('Preload failed:', data.message);
                }
                if (data.success && data.data && data.data.length > 0) {
                    console.log('Loaded cities:', data.data.map(c => c.name).join(', '));
                    // Cache the cities for this country
                    this.cachedCitiesForCountry = data.data;
                    this.cachedCountryName = countryName;
                    this.citySearchResults = data.data;
                    return;
                }
            } catch (e) {
                console.log('Database cities load failed', e);
            }
            
            // No fallback: countries and cities must come from our database
            this.citySearchResults = [];
            this.cachedCitiesForCountry = [];
            this.cachedCountryName = countryName;
        },

        async loadDepartments() {
            try {
                const response = await fetch(`${window.appConfig?.apiUrl}/v1/departments`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include'
                });
                const data = await response.json();
                if (data.success) {
                    this.departments = data.data;
                }
            } catch (error) {
                console.error('Failed to load departments:', error);
            }
        },

        async loadDestinations() {
            try {
                const response = await fetch(`${window.appConfig?.apiUrl}/v1/countries`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include'
                });
                const data = await response.json();
                if (data.success) {
                    this.destinations = (data.data || []).map(dest => {
                        // DB returns: {id, name, slug, iso2, iso3}
                        // name is the country name from the countries table
                        const id = dest.id;
                        const name = dest.name || 'Destination';
                        const iso2 = dest.iso2 || '';
                        const iso3 = dest.iso3 || '';
                        const code = iso2 || iso3;
                        const labelParts = [name];
                        if (code) labelParts.push(code);
                        return {
                            ...dest,
                            id,
                            name,
                            country: name,
                            code,
                            iso2,
                            iso3,
                            displayLabel: labelParts.join(' · ')
                        };
                    });
                }
            } catch (error) {
                console.error('Failed to load destinations:', error);
            }
        },

        getSelectedDestinationContext() {
            const destId = Number(this.form.primary_destination);
            const selected = this.destinations.find(d => d.id === destId);
            if (!selected) {
                console.warn('Destination not found for ID:', this.form.primary_destination, 'Available:', this.destinations.map(d => ({ id: d.id, name: d.name })));
            }
            return {
                countryName: selected?.country || selected?.country_name || selected?.name || '',
                countryCode: (selected?.iso2 || selected?.country_code || selected?.code || '').toLowerCase()
            };
        },

        normalizePlaceResult(place, fallbackInput = '') {
            const structured = place.structured_formatting || {};
            const terms = place.terms || [];
            const mainText = place.main_text || structured.main_text || place.description || fallbackInput;
            const secondaryText = place.secondary_text || structured.secondary_text || place.description || '';
            const countryTerm = terms.length ? terms[terms.length - 1].value : '';
            const regionTerm = terms.length > 2 ? terms[terms.length - 2].value : (terms.length === 2 ? terms[0].value : '');
            const cityTerm = terms.length ? terms[0].value : mainText;
            const labelParts = [mainText];
            if (regionTerm && regionTerm !== mainText && regionTerm !== countryTerm) labelParts.push(regionTerm);
            if (countryTerm) labelParts.push(countryTerm);
            const label = labelParts.join(' · ');

            return {
                mainText,
                secondaryText,
                city: cityTerm,
                region: regionTerm && regionTerm !== countryTerm ? regionTerm : '',
                countryName: countryTerm || secondaryText,
                label
            };
        },

        calculateDuration() {
            if (this.form.start_date && this.form.end_date) {
                const start = new Date(this.form.start_date);
                const end = new Date(this.form.end_date);
                const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
                this.form.duration_days = diff > 0 ? diff : 0;
                this.ensureCalendarDefaults();
                this.syncAssignments();
                this.applyCityTotalsToAssignments();
                this.updateCityDates();
            } else if (this.form.start_date) {
                this.ensureCalendarDefaults();
            }

            this.buildItinerary();

            if (this.currentStep >= 3) {
                this.initResourcesStep();
            }
        },

        async generateWithAI() {
            if (!this.aiPrompt) return;
            
            this.aiLoading = true;
            try {
                const requestBody = { 
                    prompt: this.aiPrompt
                };
                
                // Only include department_id if it has a valid value
                if (this.form.department_id) {
                    requestBody.department_id = parseInt(this.form.department_id);
                }
                
                const response = await fetch(`${window.appConfig?.apiUrl}/v1/offers/generate-from-prompt`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    credentials: 'include',
                    body: JSON.stringify(requestBody)
                });
                
                const data = await response.json();
                if (data.success) {
                    this.aiResult = data.data;
                } else {
                    alert('AI generation failed: ' + data.message);
                }
            } catch (error) {
                console.error('AI generation error:', error);
                alert('Failed to generate offer with AI');
            } finally {
                this.aiLoading = false;
            }
        },

        applyAISuggestion() {
            if (!this.aiResult) return;
            
            // Apply AI suggestions to form
            if (this.aiResult.title) this.form.title = this.aiResult.title;
            if (this.aiResult.duration_days) this.form.duration_days = this.aiResult.duration_days;
            if (this.aiResult.destination) this.form.primary_destination = this.aiResult.destination;
            if (this.aiResult.travelers) this.form.travelers = { ...this.form.travelers, ...this.aiResult.travelers };
            
            this.aiResult = null;
        },

        async searchClients() {
            if (this.clientSearchQuery.length < 1) {
                this.clientSuggestions = [];
                return;
            }
            try {
                const resp = await fetch(`${window.appConfig?.apiUrl}/v1/customers/search?query=${encodeURIComponent(this.clientSearchQuery)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include'
                });
                const data = await resp.json();
                this.clientSuggestions = data.success ? data.data : [];
            } catch (e) {
                console.error('Customer search failed', e);
                this.clientSuggestions = [];
            }
        },

        selectClient(client) {
            // Map database customer_type back to UI values
            const typeMap = {
                'individual': 'b2c',
                'corporate': 'b2b',
                'group': 'b2b'
            };
            
            this.form.client_id = client.id;
            this.form.client_name = client.name;
            this.form.client_type = typeMap[client.type] || 'b2c';
            this.form.client_email = client.email || '';
            this.form.client_phone = client.phone || '';
            this.clientSuggestions = [];
            this.clientSearchQuery = '';
            this.showClientSearch = false;
        },

        clearClient() {
            this.form.client_id = null;
            this.form.company_id = null;
            this.selectedCompany = null;
            this.form.client_name = '';
            this.form.client_email = '';
            this.form.client_phone = '';
            this.form.client_nationality = '';
            this.form.client_country = '';
            this.form.client_source = '';
            this.clientSearchQuery = '';
        },

        // Open create client modal and inherit type from main section
        openCreateClientModal() {
            // Inherit the client type from the main section selection
            this.newClientForm.type = this.form.client_type;
            this.showCreateCustomerModal = true;
        },

        async createNewClient() {
            if (!this.newClientForm.name || !this.newClientForm.phone) {
                alert('Please fill in name and phone');
                return;
            }

            // For B2B clients, company selection is required
            if (this.newClientForm.type === 'b2b' && !this.newClientForm.company_id) {
                alert('Please select a company for B2B clients');
                return;
            }

            this.creatingClient = true;
            
            try {
                const parts = this.newClientForm.name.trim().split(' ');
                const first = parts[0];
                const last = parts.slice(1).join(' ');
                
                const resp = await fetch(`${window.appConfig?.apiUrl}/v1/customers`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        first_name: first,
                        last_name: last,
                        email: this.newClientForm.email || null,
                        phone: this.newClientForm.phone,
                        nationality: this.newClientForm.nationality || null,
                        country: this.newClientForm.country || null,
                        source: this.newClientForm.source || null,
                        customer_type: this.newClientForm.type,
                        company_id: this.newClientForm.company_id || null
                    })
                });
                
                const data = await resp.json();
                if (data.success) {
                    // Select the newly created client
                    this.selectClient(data.data);
                    
                    // Close modal and reset form
                    this.showCreateCustomerModal = false;
                    this.newClientForm = {
                        name: '',
                        phone: '',
                        email: '',
                        nationality: '',
                        country: '',
                        source: '',
                        type: 'b2c',
                        company_id: null,
                        company_name: ''
                    };
                    
                    alert('Client created successfully!');
                } else {
                    alert('Failed to create client: ' + (data.message || 'Unknown error'));
                }
            } catch (e) {
                console.error('Create customer failed', e);
                alert('Unable to create client');
            } finally {
                this.creatingClient = false;
            }
        },

        // Company search functions for B2B client creation
        async searchCompanies() {
            const q = (this.companySearch || '').trim();
            const tenantId = window.appConfig?.tenantId || document.querySelector('meta[name="tenant-id"]')?.content || '';
            try {
                if (q.length === 0) {
                    const response = await fetch(`${window.appConfig?.apiUrl}/v1/companies`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Tenant-ID': tenantId,
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        credentials: 'include'
                    });
                    const data = await response.json();
                    this.companyResults = data.success ? (data.data || []).slice(0, 15) : [];
                    this.showCompanyResults = true;
                    return;
                }

                if (q.length < 2) {
                    const response = await fetch(`${window.appConfig?.apiUrl}/v1/companies`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Tenant-ID': tenantId,
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        credentials: 'include'
                    });
                    const data = await response.json();
                    const list = data.success ? (data.data || []) : [];
                    const searchLower = q.toLowerCase();
                    this.companyResults = list.filter(c => (c.name || '').toLowerCase().includes(searchLower)).slice(0, 15);
                    this.showCompanyResults = true;
                    return;
                }

                const response = await fetch(`${window.appConfig?.apiUrl}/v1/companies/search?query=${encodeURIComponent(q)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Tenant-ID': tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include'
                });
                const data = await response.json();
                this.companyResults = data.success ? (data.data || []) : [];
                this.showCompanyResults = true;
            } catch (error) {
                console.error('Company search failed:', error);
                this.companyResults = [];
                this.showCompanyResults = false;
            }
        },

        selectCompany(company) {
            this.newClientForm.company_id = company.id;
            this.newClientForm.company_name = company.name;
            this.companySearch = '';
            this.showCompanyResults = false;
            this.companyResults = [];
        },

        clearCompany() {
            this.newClientForm.company_id = null;
            this.newClientForm.company_name = '';
            this.companySearch = '';
        },

        async createCustomer() {
            if (!this.canCreateCustomer) {
                alert('Please fill in all required fields: Name, Phone, Type, and Department');
                return;
            }
            const parts = this.form.client_name.trim().split(' ');
            const first = parts[0];
            const last = parts.slice(1).join(' ');
            try {
                const resp = await fetch(`${window.appConfig?.apiUrl}/v1/customers`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        first_name: first,
                        last_name: last,
                        email: this.form.client_email || null,
                        phone: this.form.client_phone,
                        nationality: this.form.client_nationality || null,
                        country: this.form.client_country || null,
                        source: this.form.client_source || null,
                        customer_type: this.form.client_type
                    })
                });
                const data = await resp.json();
                if (data.success) {
                    this.selectClient(data.data);
                    alert('Client created successfully!');
                } else {
                    alert('Failed to create client: ' + (data.message || 'Unknown error'));
                }
            } catch (e) {
                console.error('Create customer failed', e);
                alert('Unable to create client');
            }
        },

        get canCreateCustomer() {
            return this.form.client_name && 
                   this.form.client_phone && 
                   this.form.client_type && 
                   this.form.department_id;
        },

        // Company methods for B2B
        async searchCompanies() {
            const q = (this.companySearch || '').trim();
            const tenantId = window.appConfig?.tenantId || document.querySelector('meta[name="tenant-id"]')?.content || '';
            try {
                // On focus (empty query): show initial tenant companies
                if (q.length === 0) {
                    const resp = await fetch(`${window.appConfig?.apiUrl}/v1/companies`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Tenant-ID': tenantId,
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        credentials: 'include'
                    });
                    const data = await resp.json();
                    const list = data.success ? (data.data || []) : [];
                    this.companyResults = list.slice(0, 15);
                    this.showCompanyDropdown = true;
                    return;
                }

                // For 1-char queries (backend requires >=2), fetch index and filter locally
                if (q.length < 2) {
                    const resp = await fetch(`${window.appConfig?.apiUrl}/v1/companies`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Tenant-ID': tenantId,
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        credentials: 'include'
                    });
                    const data = await resp.json();
                    const list = data.success ? (data.data || []) : [];
                    const searchLower = q.toLowerCase();
                    this.companyResults = list
                        .filter(c => (c.name || '').toLowerCase().includes(searchLower))
                        .slice(0, 15);
                    this.showCompanyDropdown = true;
                    return;
                }

                // Normal search for >=2 characters
                const resp = await fetch(`${window.appConfig?.apiUrl}/v1/companies/search?query=${encodeURIComponent(q)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Tenant-ID': tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include'
                });
                const data = await resp.json();
                this.companyResults = data.success ? (data.data || []) : [];
                this.showCompanyDropdown = true;
            } catch (e) {
                console.error('Company search failed', e);
                this.companyResults = [];
            }
        },

        selectCompany(company) {
            this.selectedCompany = company;
            this.form.company_id = company.id;
            this.form.client_name = company.name;
            this.companySearch = '';
            this.companyResults = [];
            this.showCompanyDropdown = false;
        },

        clearCompany() {
            this.selectedCompany = null;
            this.form.company_id = null;
            this.companySearch = '';
        },

        // Conditional tab visibility based on offer_type
        get showAccommodationTab() {
            return ['complete', 'hotel'].includes(this.form.offer_type);
        },
        get showToursTab() {
            return ['complete', 'tours'].includes(this.form.offer_type);
        },
        get showTransportTab() {
            return ['complete', 'transport'].includes(this.form.offer_type);
        },
        get showFlightsTab() {
            return ['complete', 'tours', 'hotel', 'transport'].includes(this.form.offer_type);
        },
        get showAddonsTab() {
            return true; // Always visible for all types
        },

        nextStep() {
            if (this.currentStep < this.steps.length - 1) {
                this.currentStep++;
                // When moving to step 2 (city assignment), ensure assignments are synced
                if (this.currentStep === 1 && this.form.is_multi_city) {
                    this.syncAssignments();
                    // Auto-distribute if no assignments exist yet
                    if (this.getAssignedNightsCount() === 0 && this.form.cities.length > 0) {
                        this.autoDistributeCities();
                    }
                }
                // When entering Resources step, auto-initialize selection
                if (this.currentStep === 3) {
                    this.initResourcesStep();
                }
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        prevStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        // City Distribution Methods
        async searchCitiesInDestination() {
            if (this.citySearch.length < 1) {
                // If search is empty, show all cached cities (if available)
                if (this.cachedCitiesForCountry !== null) {
                    this.citySearchResults = this.cachedCitiesForCountry.filter(city => 
                        !this.form.cities.find(c => c.id === city.id)
                    );
                } else {
                    this.citySearchResults = [];
                }
                return;
            }

            // If we have cached cities, filter them locally for instant results
            if (this.cachedCitiesForCountry !== null) {
                const searchLower = this.citySearch.toLowerCase();
                this.citySearchResults = this.cachedCitiesForCountry
                    .filter(city => 
                        city.name.toLowerCase().includes(searchLower) &&
                        !this.form.cities.find(c => c.id === city.id)
                    );
                console.log('Filtered from cache:', this.citySearchResults.length, 'results');
                return;
            }

            // Fallback to API search if no cache (shouldn't happen after preload)
            const { countryName, countryCode } = this.getSelectedDestinationContext();
            const url = `${window.appConfig?.apiUrl}/v1/cities/search?country_name=${encodeURIComponent(countryName)}&query=${encodeURIComponent(this.citySearch)}`;
            console.log('Search cities (no cache):', { countryName, query: this.citySearch, url });

            try {
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include'
                });
                const data = await response.json();
                console.log('Search response:', { success: data.success, count: data.data?.length, status: response.status, url: response.url });
                if (!data.success) {
                    console.warn('Search failed:', data.message);
                }
                const backendResults = (data.success && data.data?.length) ? data.data : [];
                if (backendResults.length) {
                    console.log('Found cities:', backendResults.map(c => c.name).join(', '));
                    this.citySearchResults = backendResults.filter(city => !this.form.cities.find(c => c.id === city.id));
                } else {
                    console.log('No cities found in database');
                    this.citySearchResults = [];
                }
            } catch (error) {
                console.error('Failed to search cities:', error);
                this.citySearchResults = [];
            }
        },

        async searchCitiesViaPlaces(countryContext = '', countryCode = '') {
            try {
                const resp = await fetch(`${window.appConfig?.apiUrl}/v1/locations/autocomplete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include',
                    body: JSON.stringify({ 
                        input: `${this.citySearch} ${countryContext || this.form.primary_destination || ''}`,
                        components: countryCode ? [`country:${countryCode}`] : undefined,
                        types: ['administrative_area_level_1', 'administrative_area_level_2', 'locality']
                    })
                });
                const data = await resp.json();
                if (!data.success || !data.data) return [];
                
                return data.data
                    .map(place => {
                        const parsed = this.normalizePlaceResult(place, this.citySearch);
                        let cityName = parsed.city || parsed.mainText;
                        
                        if (!cityName) return null;
                        
                        // Extract city from secondary_text if main_text is a business name
                        if (place.secondary_text) {
                            const parts = place.secondary_text.split(',').map(p => p.trim());
                            if (parts.length >= 2) {
                                const potentialCity = parts[parts.length - 2];
                                if (potentialCity && potentialCity.length < 50 && !potentialCity.match(/^(The|A |An )/i)) {
                                    cityName = potentialCity;
                                }
                            }
                        }
                        
                        // Skip known business keywords
                        const lowerName = cityName.toLowerCase();
                        if (lowerName.includes('telecom') || 
                            lowerName.includes('connectivity') ||
                            lowerName.includes('corp') ||
                            lowerName.includes('ltd') ||
                            lowerName.includes('hotel') ||
                            lowerName.includes('plaza') ||
                            lowerName.includes('center') ||
                            lowerName.includes('market') ||
                            lowerName.match(/^(we |te |el )/i)) {
                            return null;
                        }
                        
                        return {
                            id: place.place_id,
                            place_id: place.place_id,
                            name: cityName,
                            country: parsed.countryName || parsed.secondaryText || '',
                            region: parsed.region || '',
                            description: parsed.label,
                            destination_id: null
                        };
                    })
                    .filter(city => city && !this.form.cities.find(c => c.id === city.id));
            } catch (e) {
                console.error('Places autocomplete failed', e);
                return [];
            }
        },

        async loadCitiesForCountryViaPlaces(countryName) {
            try {
                const { countryCode } = this.getSelectedDestinationContext();
                
                // Query for the country to get administrative divisions
                const resp = await fetch(`${window.appConfig?.apiUrl}/v1/locations/autocomplete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include',
                    body: JSON.stringify({ 
                        // Query for the country to get administrative divisions
                        input: countryName,
                        components: countryCode ? [`country:${countryCode}`] : undefined,
                        types: ['administrative_area_level_1', 'administrative_area_level_2', 'locality']
                    })
                });
                
                const data = await resp.json();
                if (!data.success || !data.data) return [];
                
                const seen = new Set();
                return data.data
                    .map(place => {
                        const parsed = this.normalizePlaceResult(place, countryName);
                        let cityName = parsed.city || parsed.mainText;
                        
                        if (!cityName) return null;
                        
                        // Extract city from secondary_text if main_text is a business name
                        // e.g., "Ahmed Oraby, Gazirat Mit Oqbah, Agouza, Egypt" -> "Agouza"
                        if (place.secondary_text) {
                            const parts = place.secondary_text.split(',').map(p => p.trim());
                            // Get the second-to-last part (before country name)
                            if (parts.length >= 2) {
                                const potentialCity = parts[parts.length - 2];
                                // Use this if it's not too long (business names tend to be longer)
                                if (potentialCity && potentialCity.length < 50 && !potentialCity.match(/^(The|A |An )/i)) {
                                    cityName = potentialCity;
                                }
                            }
                        }
                        
                        // Skip very short names (likely abbreviations)
                        if (cityName.length < 2) return null;
                        
                        // Skip if it's exactly the country name
                        if (cityName.toLowerCase() === countryName.toLowerCase()) return null;
                        
                        // Skip known business keywords
                        const lowerName = cityName.toLowerCase();
                        if (lowerName.includes('telecom') || 
                            lowerName.includes('connectivity') ||
                            lowerName.includes('corp') ||
                            lowerName.includes('ltd') ||
                            lowerName.includes('hotel') ||
                            lowerName.includes('plaza') ||
                            lowerName.includes('center') ||
                            lowerName.includes('market') ||
                            lowerName.match(/^(we |te |el )/i)) {
                            return null;
                        }
                        
                        // Filter out duplicates
                        if (seen.has(lowerName)) return null;
                        seen.add(lowerName);
                        
                        return {
                            id: place.place_id,
                            place_id: place.place_id,
                            name: cityName,
                            country: parsed.countryName || countryName,
                            region: parsed.region || '',
                            description: parsed.label,
                            destination_id: null
                        };
                    })
                    .filter(Boolean)
                    .slice(0, 25);
                    
            } catch (e) {
                console.error('Places autocomplete failed for country preload', e);
                return [];
            }
        },

        async addCity(city) {
            if (!this.form.cities.find(c => c.id === city.id)) {
                // If city has a place_id (from Google Places), save it to the database
                if (city.place_id && !city.destination_id) {
                    try {
                        const payload = {
                            name: city.name,
                            country_name: city.country,
                            place_id: city.place_id,
                            formatted_address: city.description || '',
                            place_data: city.place_data || {}
                        };
                        
                        const resp = await fetch(`${window.appConfig?.apiUrl}/v1/cities`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Tenant-ID': window.appConfig?.tenantId,
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            credentials: 'include',
                            body: JSON.stringify(payload)
                        });
                        
                        const data = await resp.json();
                        if (data.success) {
                            console.log('City saved to database:', data.data);
                        }
                    } catch (e) {
                        console.error('Failed to save city to database:', e);
                        // Continue anyway - we still want to add it to the form
                    }
                }
                
                const newCity = {
                    id: city.id,
                    name: city.name,
                    country: city.country,
                    region: city.region || '',
                    description: city.description || city.label || '',
                    destination_id: city.destination_id || null,
                    nights: 0,
                    start_date: null,
                    end_date: null,
                    areas: [],
                    selectedAreas: [],
                    areaSearch: '',
                    areaResults: []
                };
                this.form.cities.push(newCity);
                if (!this.activeCityId) {
                    this.activeCityId = newCity.id;
                }
                this.citySearch = '';
                this.citySearchResults = [];
                this.loadAreasForCity(newCity);
                this.syncAssignments();
            }
        },

        setActiveCity(cityId) {
            this.activeCityId = cityId;
        },

        removeCity(index) {
            const removed = this.form.cities[index];
            this.form.cities.splice(index, 1);
            if (removed) {
                this.dayAssignments = this.dayAssignments.map(day => day.cityId === removed.id ? { ...day, cityId: null } : day);
            }
            if (this.activeCityId === removed?.id) {
                this.activeCityId = this.form.cities[0]?.id || null;
            }
            this.recalculateCityNightsFromAssignments();
        },

        async loadAreasForCity(city) {
            try {
                const resp = await fetch(`${window.appConfig?.apiUrl}/v1/areas?city=${encodeURIComponent(city.name)}` ,{
                    headers: {
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include'
                });
                const data = await resp.json();
                city.areas = data.success ? data.data : [];
            } catch (e) {
                console.error('Load areas failed', e);
                city.areas = [];
            }
        },

        async searchAreaPlaces(city) {
            if (!city.areaSearch || city.areaSearch.length < 2) {
                city.areaResults = [];
                return;
            }
            try {
                // Build search query with city and country context for precise results
                const searchQuery = `${city.areaSearch} ${city.name} ${city.country}`;
                
                const resp = await fetch(`${window.appConfig?.apiUrl}/v1/locations/autocomplete` ,{
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include',
                    body: JSON.stringify({ 
                        input: searchQuery,
                        // Restrict search to the specific city if we have its place_id
                        components: city.place_id ? [`country:${city.country}`] : undefined,
                        // Include all location types to get detailed addresses
                        types: ['street_address', 'premise', 'route', 'locality', 'administrative_area_level_3']
                    })
                });
                const data = await resp.json();
                const raw = data.success ? data.data : [];
                city.areaResults = raw
                    .filter(place => {
                        // Only show results within the selected city
                        const desc = place.description || '';
                        return desc.includes(city.name) || desc.includes(city.country);
                    })
                    .map(place => {
                        const parsed = this.normalizePlaceResult(place, city.areaSearch);
                        return {
                            ...place,
                            place_id: place.place_id,
                            main_text: parsed.mainText,
                            secondary_text: parsed.label || parsed.secondaryText,
                            country: parsed.countryName,
                            region: parsed.region,
                            city: parsed.city,
                            label: parsed.label
                        };
                    });
            } catch (e) {
                console.error('Area autocomplete failed', e);
                city.areaResults = [];
            }
        },

        async ingestArea(place, city) {
            if (!city.destination_id) {
                // attempt to resolve destination id by city name
                // areas endpoint relies on Destination; skip ingest if unknown
                console.warn('No destination_id for city; cannot ingest area');
                return;
            }
            try {
                const resp = await fetch(`${window.appConfig?.apiUrl}/v1/geo/ingest-area` ,{
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include',
                    body: JSON.stringify({ place_id: place.place_id, destination_id: city.destination_id })
                });
                const data = await resp.json();
                if (data.success && data.data?.area) {
                    city.areas.push(data.data.area);
                    city.areaResults = [];
                    city.areaSearch = '';
                } else {
                    alert('Failed to add area: ' + (data.message || 'Unknown error'));
                }
            } catch (e) {
                console.error('Ingest area failed', e);
                alert('Unable to add area');
            }
        },

        // Location Management Methods (Google Maps API)
        async searchLocations(dayIndex, cityName) {
            if (!this.locationSearch || this.locationSearch.length < 1) {
                this.locationSearchResults = [];
                this.locationSearchLoading = false;
                return;
            }

            this.locationSearchLoading = true;

            console.log('🔍 Searching locations:', {
                query: this.locationSearch,
                cityName: cityName,
                dayIndex: dayIndex
            });

            try {
                // Get the city object to extract country information
                const timelineItem = this.getTimelineItems()[dayIndex];
                const cityId = timelineItem?.cityId;
                const city = this.form.cities.find(c => c.id === cityId);

                // Resolve country with multiple fallbacks to avoid using stale country
                const destinationContext = this.getSelectedDestinationContext();
                const countryName = city?.country
                    || city?.country_name
                    || destinationContext.countryName
                    || 'Turkey';
                const countryCode = destinationContext.countryCode || '';

                // Build comprehensive Google Maps query
                // Format: "{query} in {city}, {country}"
                // This helps Google Maps narrow down results to the specific location
                const comprehensiveQuery = `${this.locationSearch} in ${cityName}, ${countryName}`;
                
                console.log('📍 Google Maps query:', comprehensiveQuery);

                const params = new URLSearchParams();
                params.set('query', comprehensiveQuery);
                if (countryName) params.set('country', countryName);
                if (countryCode) params.set('country_code', countryCode);

                const url = `${window.appConfig?.apiUrl}/v1/locations/search?${params.toString()}`;
                
                console.log('🌐 API URL:', url);

                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId || '',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    credentials: 'include'
                });

                console.log('📥 Response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                
                console.log('✅ API Response:', data);

                if (data.success && data.data && Array.isArray(data.data)) {
                    this.locationSearchResults = data.data;
                    console.log(`✨ Found ${data.data.length} locations`);
                    
                    // Fetch first photo for each result (lazy load)
                    this.fetchLocationPhotos();
                } else {
                    this.locationSearchResults = [];
                    console.warn('⚠️ No results or invalid response format');
                }
            } catch (e) {
                console.error('❌ Location search failed:', e);
                this.locationSearchResults = [];
                
                // Show user-friendly error
                if (e.message.includes('HTTP')) {
                    console.error('API Error:', e.message);
                } else {
                    console.error('Network or parsing error:', e);
                }
            } finally {
                this.locationSearchLoading = false;
            }
        },

        async fetchLocationPhotos() {
            // Fetch photos for the first 5 results to avoid rate limiting
            const resultsToFetch = this.locationSearchResults.slice(0, 5);
            
            for (const location of resultsToFetch) {
                if (location.photo_url) continue; // Already has photo
                
                try {
                    const url = `${window.appConfig?.apiUrl}/v1/locations/details/${location.place_id}`;
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Tenant-ID': window.appConfig?.tenantId || '',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'include'
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.success && data.data?.photos?.[0]) {
                            location.photo_url = data.data.photos[0].thumbnail_url;
                        }
                    }
                } catch (e) {
                    console.warn('Failed to fetch photo for:', location.place_id);
                }
            }
        },

        addLocationToDay(dayIndex, location) {
            // Find or create day location entry
            let dayLocation = this.dayLocations.find(dl => dl.dayIndex === dayIndex);
            if (!dayLocation) {
                dayLocation = { dayIndex, locations: [] };
                this.dayLocations.push(dayLocation);
            }

            // Check if location already added
            if (dayLocation.locations.some(l => l.place_id === location.place_id)) {
                return;
            }

            // Add location (handle both autocomplete and details API formats)
            dayLocation.locations.push({
                place_id: location.place_id,
                name: location.main_text || location.name || location.description,
                formatted_address: location.description || location.formatted_address || location.secondary_text,
                main_text: location.main_text,
                secondary_text: location.secondary_text,
                photo_url: location.photo_url || (location.photos?.[0]?.thumbnail_url),
                lat: location.geometry?.location?.lat || location.lat,
                lng: location.geometry?.location?.lng || location.lng,
                types: location.types || [],
                city: location.city || ''
            });

            // Clear search
            this.locationSearch = '';
            this.locationSearchResults = [];
        },

        removeLocationFromDay(dayIndex, locationIndex) {
            const dayLocation = this.dayLocations.find(dl => dl.dayIndex === dayIndex);
            if (dayLocation) {
                dayLocation.locations.splice(locationIndex, 1);
            }
        },

        // Drag and drop handling for locations and tours
        startDrag(type, dayIndex, fromIndex) {
            this.dragContext = { type, dayIndex, fromIndex };
        },

        handleDrop(type, dayIndex, toIndex) {
            const ctx = this.dragContext;
            if (!ctx.type || ctx.type !== type) return;
            if (ctx.dayIndex !== null && ctx.dayIndex !== dayIndex) return;

            const moveItem = (arr) => {
                if (!arr || ctx.fromIndex === null || ctx.fromIndex === undefined) return;
                const [item] = arr.splice(ctx.fromIndex, 1);
                arr.splice(toIndex, 0, item);
            };

            if (type === 'day-location') {
                const dayLocation = this.dayLocations.find(dl => dl.dayIndex === dayIndex);
                if (dayLocation) moveItem(dayLocation.locations);
            } else if (type === 'day-tour') {
                const dayTour = this.dayTours.find(dt => dt.dayIndex === dayIndex);
                if (dayTour) moveItem(dayTour.tours);
            } else if (type === 'newtour-location') {
                moveItem(this.newTourData.locations);
            }

            this.dragContext = { type: null, dayIndex: null, fromIndex: null };
        },

        getDayLocations(dayIndex) {
            const dayLocation = this.dayLocations.find(dl => dl.dayIndex === dayIndex);
            return dayLocation ? dayLocation.locations : [];
        },

            getDayTours(dayIndex) {
                const dayTour = this.dayTours.find(dt => dt.dayIndex === dayIndex);
                return dayTour ? dayTour.tours : [];
            },

            addTourToDay(dayIndex, tour) {
                let dayTour = this.dayTours.find(dt => dt.dayIndex === dayIndex);
                if (!dayTour) {
                    dayTour = { dayIndex, tours: [] };
                    this.dayTours.push(dayTour);
                }
                if (!dayTour.tours.some(t => t.id === tour.id)) {
                    dayTour.tours.push(tour);
                }
            },

            // Close the tour autocomplete dropdown (keep input value)
            closeTourSearch() {
                this.tourSearchLoading = false;
                this.tourSearchResults = [];
            },

            // Wrapper to add tour then close autocomplete
            selectTourForDay(dayIndex, tour) {
                this.addTourToDay(dayIndex, tour);
                this.closeTourSearch();
            },

            removeTourFromDay(dayIndex, tourIndex) {
                const dayTour = this.dayTours.find(dt => dt.dayIndex === dayIndex);
                if (dayTour) {
                    dayTour.tours.splice(tourIndex, 1);
                }
            },

            async searchTours(cityName) {
                const query = (this.tourSearch || '').trim();
                const localMatches = (this.availableTours || [])
                    .filter(t => !query || (t.name || '').toLowerCase().includes(query.toLowerCase()))
                    .map(t => ({
                        ...t,
                        price: t.price_per_person ?? t.price ?? 0,
                        source: t.source || 'local'
                    }));

                if (!cityName || cityName.trim().length === 0) {
                    this.tourSearchResults = localMatches;
                    return;
                }

                // Show local matches immediately for instant autocomplete feedback
                this.tourSearchResults = localMatches;

                if (query.length === 0) {
                    this.tourSearchLoading = false;
                    return;
                }

                this.tourSearchLoading = true;
                try {
                    const url = `${window.appConfig?.apiUrl}/v1/tours/search`;
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Tenant-ID': window.appConfig?.tenantId || '',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({ city: cityName, search: query }),
                        credentials: 'include'
                    });
                    if (response.ok) {
                        const data = await response.json();
                        const remoteTours = (data.data || data.tours || []).map(t => ({
                            ...t,
                            price: t.price_per_person ?? t.price ?? 0,
                            source: 'remote'
                        }));

                        // Merge remote + local without duplicates
                        const seen = new Set();
                        const merged = [];
                        [...remoteTours, ...localMatches].forEach(t => {
                            const key = String(t.id);
                            if (!seen.has(key)) {
                                seen.add(key);
                                merged.push(t);
                            }
                        });

                        this.tourSearchResults = merged;
                    } else {
                        this.tourSearchResults = localMatches;
                    }
                } catch (error) {
                    console.error('Error searching tours:', error);
                    this.tourSearchResults = localMatches;
                } finally {
                    this.tourSearchLoading = false;
                }
            },

            initCreateTourMode(dayIndex) {
                this.createTourMode = true;
                this.showTourModal = true;
                this.tourModalDayIndex = dayIndex;
                this.newTourData = {
                    name: '',
                    duration: '',
                    price: '',
                    notes: '',
                    locations: [],
                    dayIndex
                };
            },

            addLocationToNewTour(location) {
                if (!this.newTourData.locations.some(l => l.place_id === location.place_id)) {
                    this.newTourData.locations.push(location);
                }
            },

            removeLocationFromNewTour(locationIndex) {
                this.newTourData.locations.splice(locationIndex, 1);
            },

            saveTourWithLocations() {
                if (!this.newTourData.name.trim()) {
                    alert('Please enter a tour name');
                    return;
                }
                if (this.newTourData.locations.length === 0) {
                    alert('Please add at least one location to the tour');
                    return;
                }
                const tour = {
                    id: 'tour-' + Date.now(),
                    name: this.newTourData.name,
                    duration: this.newTourData.duration,
                    price: parseFloat(this.newTourData.price) || 0,
                    notes: this.newTourData.notes,
                    locations: [...this.newTourData.locations],
                    isCustom: true,
                    created_at: new Date().toISOString()
                };
                const dayIndex = this.newTourData.dayIndex;
                this.addTourToDay(dayIndex, tour);
                this.availableTours.push(tour);
                this.createTourMode = false;
                this.showTourModal = false;
                this.tourModalDayIndex = null;
                this.newTourData = {
                    name: '',
                    duration: '',
                    price: '',
                    notes: '',
                    locations: []
                };
                console.log('✨ Tour created:', tour);
            },

            cancelCreateTour() {
                this.createTourMode = false;
                this.showTourModal = false;
                this.tourModalDayIndex = null;
                this.newTourData = {
                    name: '',
                    duration: '',
                    price: '',
                    notes: '',
                    locations: []
                };
            },

            switchLocationTab(tab) {
                this.activeDayTab = tab;
            },

        async openLocationPreview(dayIndex, location) {
            console.log('🔍 Opening preview for:', location);
            this.locationPreviewModal = true;
            this.locationPreviewLoading = true;
            this.locationPreviewData = null;
            this.currentPhotoIndex = 0;
            this.selectedLocation = { dayIndex, location };

            try {
                const url = `${window.appConfig?.apiUrl}/v1/locations/details/${location.place_id}`;
                console.log('📍 Fetching details from:', url);

                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId || '',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    credentials: 'include'
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                console.log('✅ Location details received:', data);

                if (data.success && data.data) {
                    this.locationPreviewData = data.data;
                } else {
                    throw new Error('Invalid response format');
                }
            } catch (error) {
                console.error('❌ Failed to load location details:', error);
                alert('Failed to load location details. Please try again.');
                this.locationPreviewModal = false;
            } finally {
                this.locationPreviewLoading = false;
            }
        },

        assignLocationFromPreview() {
            if (!this.selectedLocation || !this.locationPreviewData) {
                console.warn('No location selected for assignment');
                return;
            }

            const { dayIndex } = this.selectedLocation;
            
            // Merge location data with full details
            const fullLocation = {
                ...this.selectedLocation.location,
                ...this.locationPreviewData,
                name: this.locationPreviewData.name,
                formatted_address: this.locationPreviewData.address
            };

            console.log('✨ Assigning location to day', dayIndex, fullLocation);
            this.addLocationToDay(dayIndex, fullLocation);
            
            // Close modal
            this.locationPreviewModal = false;
            this.locationPreviewData = null;
            this.selectedLocation = null;
        },

        ensureCalendarDefaults() {
            if (!this.form.start_date) return;
            const start = new Date(this.form.start_date);
            if (this.calendarMonth === null || this.calendarYear === null) {
                this.calendarMonth = start.getMonth();
                this.calendarYear = start.getFullYear();
            }
        },

        toDateKey(date) {
            const d = new Date(date);
            return d.toISOString().split('T')[0];
        },

        syncAssignments() {
            if (!this.form.start_date || this.totalNights <= 0) {
                this.dayAssignments = [];
                return;
            }

            this.ensureCalendarDefaults();

            const existing = new Map(this.dayAssignments.map(d => [d.date, d.cityId]));
            const start = new Date(this.form.start_date);
            const nights = this.totalNights;
            const nextAssignments = [];

            for (let i = 0; i < nights; i++) {
                const d = new Date(start);
                d.setDate(d.getDate() + i);
                const key = this.toDateKey(d);
                nextAssignments.push({ date: key, cityId: existing.get(key) ?? null });
            }

            this.dayAssignments = nextAssignments;
            this.recalculateCityNightsFromAssignments();
            this.buildItinerary();
        },

        clearAllAssignments() {
            this.syncAssignments();
            this.dayAssignments = this.dayAssignments.map(day => ({ ...day, cityId: null }));
            this.form.cities.forEach(city => {
                city.nights = 0;
                city.start_date = null;
                city.end_date = null;
            });
            this.buildItinerary();
        },

        assignCityToSingle(cityId, delta) {
            const city = this.form.cities.find(c => c.id === cityId);
            if (!city) return;
            city.nights = Math.max(0, (city.nights || 0) + delta);
            this.applyCityTotalsToAssignments();
        },

        assignCityToDay(dateStr, cityId) {
            if (!dateStr || !cityId) return;
            const assignment = this.dayAssignments.find(d => d.date === dateStr);
            if (assignment) {
                assignment.cityId = cityId;
            }
            this.recalculateCityNightsFromAssignments();
            this.buildItinerary();
        },

        assignCityToRange(startDate, endDate, cityId) {
            if (!startDate || !endDate || !cityId) return;
            const start = new Date(startDate);
            const end = new Date(endDate);
            const from = start <= end ? start : end;
            const to = start <= end ? end : start;

            this.dayAssignments = this.dayAssignments.map(day => {
                const current = new Date(day.date);
                const inRange = current >= from && current <= to;
                return inRange && this.isDateInTripRange(day.date) ? { ...day, cityId } : day;
            });

            this.recalculateCityNightsFromAssignments();
            this.buildItinerary();
        },

        handleRangeSelection(dateStr) {
            if (!this.rangeSelection.start) {
                this.rangeSelection = { start: dateStr, end: null, selecting: true };
                return;
            }

            this.rangeSelection.end = dateStr;
            this.rangeSelection.selecting = false;
            this.assignCityToRange(this.rangeSelection.start, this.rangeSelection.end, this.activeCityId);
            this.clearRangeSelection();
        },

        handleDayClick(day) {
            if (!day.date || !day.inTripRange || !this.activeCityId) return;
            if (this.calendarMode === 'range') {
                this.handleRangeSelection(day.date);
            } else {
                this.assignCityToDay(day.date, this.activeCityId);
            }
        },

        handleDayHover(day) {
            if (this.rangeSelection.selecting && day.date) {
                this.hoveredDay = day;
            }
        },

        clearHover() {
            this.hoveredDay = null;
        },

        clearRangeSelection() {
            this.rangeSelection = { start: null, end: null, selecting: false };
            this.hoveredDay = null;
        },

        get calendarDays() {
            if (this.calendarMonth === null || this.calendarYear === null) {
                this.ensureCalendarDefaults();
            }
            if (this.calendarMonth === null || this.calendarYear === null) return [];

            const firstDay = new Date(this.calendarYear, this.calendarMonth, 1);
            const daysInMonth = new Date(this.calendarYear, this.calendarMonth + 1, 0).getDate();
            const blanks = firstDay.getDay();
            const days = [];

            for (let i = 0; i < blanks; i++) {
                days.push({ date: null, dayNumber: null, inTripRange: false, cityId: null });
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const dateObj = new Date(this.calendarYear, this.calendarMonth, day);
                const dateStr = this.toDateKey(dateObj);
                const assignment = this.dayAssignments.find(d => d.date === dateStr);
                const inRange = this.isDateInTripRange(dateStr);
                days.push({
                    date: dateStr,
                    dayNumber: day,
                    inTripRange: inRange,
                    cityId: assignment?.cityId || null
                });
            }

            return days;
        },

        getDayClasses(day) {
            if (!day.date) return 'bg-transparent border-transparent cursor-default';
            if (!day.inTripRange) return 'bg-gray-50 text-gray-400 border-gray-200 cursor-not-allowed';
            if (day.cityId) return 'border-blue-200 bg-blue-50 text-blue-800';
            return 'border-gray-200 bg-white hover:bg-blue-50 text-gray-800';
        },

        getDayNumberClasses(day) {
            if (!day.date || !day.inTripRange) return 'text-gray-400';
            return day.cityId ? 'font-semibold text-blue-800' : 'text-gray-800';
        },

        isDateInTripRange(dateStr) {
            if (!this.form.start_date || this.totalNights <= 0) return false;
            const start = new Date(this.form.start_date);
            const end = new Date(start);
            end.setDate(end.getDate() + this.totalNights);
            const current = new Date(dateStr);
            return current >= start && current < end;
        },

        navigateCalendar(direction) {
            if (direction === 'prev') {
                this.calendarMonth -= 1;
            } else {
                this.calendarMonth += 1;
            }
            if (this.calendarMonth < 0) {
                this.calendarMonth = 11;
                this.calendarYear -= 1;
            }
            if (this.calendarMonth > 11) {
                this.calendarMonth = 0;
                this.calendarYear += 1;
            }
        },

        getMonthYearDisplay() {
            if (this.calendarMonth === null || this.calendarYear === null) return '';
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return `${monthNames[this.calendarMonth]} ${this.calendarYear}`;
        },

        getCityNights(cityId) {
            const assigned = this.dayAssignments.filter(day => day.cityId === cityId).length;
            if (assigned) return assigned;
            return this.form.cities.find(c => c.id === cityId)?.nights || 0;
        },

        getAssignedNightsCount() {
            return this.dayAssignments.filter(day => day.cityId).length;
        },

        getCityNameById(cityId) {
            return this.form.cities.find(c => c.id === cityId)?.name || '';
        },

        getCityIndexById(cityId) {
            return this.form.cities.findIndex(c => c.id === cityId);
        },

        getTimelineItems() {
            if (!this.form.start_date || this.totalNights <= 0) return [];
            const start = new Date(this.form.start_date);
            return this.dayAssignments.map((assignment, index) => {
                const dateObj = new Date(start);
                dateObj.setDate(dateObj.getDate() + index);
                const city = this.form.cities.find(c => c.id === assignment.cityId);
                const cityIndex = city ? this.getCityIndexById(city.id) : -1;
                return {
                    id: assignment.date,
                    dateFormatted: this.formatFullDate(assignment.date),
                    nightNumber: index + 1,
                    isAssigned: !!assignment.cityId,
                    cityName: city?.name || 'Unassigned',
                    cityColor: cityIndex >= 0 ? this.getCityColor(cityIndex) : null,
                    isLast: index === this.dayAssignments.length - 1
                };
            });
        },

        recalculateCityNightsFromAssignments() {
            const grouped = {};
            this.dayAssignments.forEach(day => {
                if (!day.cityId) return;
                const current = grouped[day.cityId] || [];
                current.push(day.date);
                grouped[day.cityId] = current;
            });

            this.form.cities.forEach(city => {
                const dates = grouped[city.id] || [];
                city.nights = dates.length;
                if (dates.length) {
                    const sorted = dates.slice().sort();
                    city.start_date = sorted[0];
                    const end = new Date(sorted[sorted.length - 1]);
                    end.setDate(end.getDate() + 1);
                    city.end_date = end;
                } else {
                    city.start_date = null;
                    city.end_date = null;
                }
            });
        },

        applyCityTotalsToAssignments() {
            if (!this.form.start_date || this.totalNights <= 0) return;
            this.syncAssignments();
            let cursor = 0;
            const assignments = [...this.dayAssignments];
            this.form.cities.forEach(city => {
                for (let i = 0; i < (city.nights || 0) && cursor < assignments.length; i++) {
                    assignments[cursor].cityId = city.id;
                    cursor++;
                }
            });
            this.dayAssignments = assignments;
            this.recalculateCityNightsFromAssignments();
            this.buildItinerary();
        },

        updateCityDates() {
            if (this.dayAssignments.length) {
                this.recalculateCityNightsFromAssignments();
                return;
            }

            if (!this.form.start_date || this.form.cities.length === 0) return;

            let currentDate = new Date(this.form.start_date);
            this.form.cities.forEach(city => {
                if (city.nights > 0) {
                    city.start_date = new Date(currentDate);
                    currentDate.setDate(currentDate.getDate() + city.nights);
                    city.end_date = new Date(currentDate);
                } else {
                    city.start_date = null;
                    city.end_date = null;
                }
            });
        },

        autoDistributeCities() {
            if (this.form.cities.length === 0 || this.totalNights === 0) {
                alert('Please add cities and set trip dates first.');
                return;
            }

            // Ensure day assignments are initialized
            this.syncAssignments();

            // Distribute nights flexibly across cities
            const cityCount = this.form.cities.length;
            const minNightsPerCity = Math.max(1, Math.floor(this.totalNights / (cityCount * 2)));
            let remainingNights = this.totalNights;
            const nightsAssigned = [];

            // Give each city a random number of consecutive nights
            for (let i = 0; i < cityCount - 1; i++) {
                const maxPossible = remainingNights - (cityCount - i - 1) * minNightsPerCity;
                const randomNights = Math.floor(Math.random() * (maxPossible - minNightsPerCity + 1)) + minNightsPerCity;
                nightsAssigned.push(randomNights);
                remainingNights -= randomNights;
            }
            // Last city gets all remaining nights
            nightsAssigned.push(remainingNights);

            // Assign nights to cities
            this.form.cities.forEach((city, index) => {
                city.nights = nightsAssigned[index];
            });

            // Create city order (optionally shuffle to randomize which city comes first)
            const cityOrder = [...this.form.cities];
            // Uncomment next line to randomize city visit order
            // cityOrder.sort(() => Math.random() - 0.5);

            // Assign days to cities consecutively (no city repetition)
            const assignments = [...this.dayAssignments];
            let dayIndex = 0;

            cityOrder.forEach(city => {
                for (let i = 0; i < city.nights; i++) {
                    if (dayIndex < assignments.length) {
                        assignments[dayIndex].cityId = city.id;
                        dayIndex++;
                    }
                }
            });

            this.dayAssignments = assignments;
            this.recalculateCityNightsFromAssignments();
            this.buildItinerary();
            
            console.log('✅ Cities auto-distributed consecutively');
        },

        getCityColor(index) {
            return this.cityColors[index % this.cityColors.length];
        },

        formatDate(date) {
            if (!date) return '';
            const d = new Date(date);
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        },

        // Resource Management Methods
        getCityForDay(dayIndex) {
            if (!this.form.is_multi_city) {
                return this.form.primary_destination;
            }

            if (this.dayAssignments.length) {
                const assignment = this.dayAssignments[Math.min(dayIndex, this.dayAssignments.length - 1)];
                if (assignment?.cityId) {
                    return this.getCityNameById(assignment.cityId);
                }
            }

            let currentNight = 0;
            for (const city of this.form.cities) {
                currentNight += city.nights;
                if (dayIndex < currentNight) {
                    return city.name;
                }
            }
            
            return this.form.cities[this.form.cities.length - 1]?.name || this.form.primary_destination;
        },

        isCityChangeDay(dayIndex) {
            if (!this.form.is_multi_city || dayIndex === 0) return false;
            
            const currentCity = this.getCityForDay(dayIndex);
            const previousCity = this.getCityForDay(dayIndex - 1);
            
            return currentCity !== previousCity;
        },

        getNextCity(dayIndex) {
            if (!this.form.is_multi_city) return null;
            return this.getCityForDay(dayIndex + 1);
        },

        formatFullDate(date) {
            if (!date) return '';
            const d = new Date(date);
            return d.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
        },

        formatDateShort(date) {
            if (!date) return '';
            const d = new Date(date);
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        },

        formatDateMedium(date) {
            if (!date) return '';
            const d = new Date(date);
            return d.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
        },

        formatDateLong(date) {
            if (!date) return '';
            const d = new Date(date);
            return d.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
        },

        getDay(dayIndex) {
            return this.itinerary[dayIndex] || {};
        },

        getCityIndexByName(cityName) {
            return this.form.cities.findIndex(c => c.name === cityName);
        },

        getResourceCount(type) {
            switch(type) {
                case 'accommodation':
                    return this.itinerary.filter(day => day.accommodation).length;
                case 'tours':
                    return this.itinerary.reduce((sum, day) => sum + (day.tours?.length || 0), 0);
                case 'transport':
                    return this.itinerary.filter(day => day.transport).length;
                case 'flights':
                    return (this.form.flight_outbound ? 1 : 0) + 
                           (this.form.internal_flights?.length || 0) + 
                           (this.form.flight_return ? 1 : 0);
                case 'addons':
                    return this.form.addons?.length || 0;
                default:
                    return 0;
            }
        },

        openResourceSelector(dayIndex, resourceType) {
            // Placeholder for resource selector modal
            // In real implementation, this would open a modal to search/select resources
            console.log('Opening resource selector:', { dayIndex, resourceType });
            
            // Demo: Add a mock resource for testing
            if (resourceType === 'accommodation' && dayIndex !== null) {
                this.itinerary[dayIndex].accommodation = {
                    id: 1,
                    name: 'Grand Hotel ' + this.itinerary[dayIndex].city,
                    room_type: 'Deluxe Double Room',
                    rating: '5★',
                    meal_plan: 'Breakfast included'
                };
            } else if (resourceType === 'tours' && dayIndex !== null) {
                if (!this.itinerary[dayIndex].tours) {
                    this.itinerary[dayIndex].tours = [];
                }
                this.itinerary[dayIndex].tours.push({
                    id: Date.now(),
                    name: 'City Tour - ' + this.itinerary[dayIndex].city,
                    duration: '4 hours',
                    type: 'Private'
                });
            } else if (resourceType === 'transport' && dayIndex !== null) {
                this.itinerary[dayIndex].transport = {
                    id: 1,
                    vehicle_type: 'Luxury Coach',
                    duration: '3 hours'
                };
            } else if (resourceType === 'flight_outbound') {
                this.form.flight_outbound = {
                    id: 1,
                    airline: 'Emirates',
                    from: 'Dubai',
                    to: this.form.primary_destination
                };
            } else if (resourceType === 'flight_return') {
                this.form.flight_return = {
                    id: 1,
                    airline: 'Emirates',
                    from: this.form.primary_destination,
                    to: 'Dubai'
                };
            } else if (resourceType === 'addon') {
                if (!this.form.addons) {
                    this.form.addons = [];
                }
                this.form.addons.push({
                    id: Date.now(),
                    name: 'SIM Card',
                    type: 'Connectivity'
                });
            }
            // Trigger reactivity synchronously
            this.itinerary = this.itinerary.slice();
            this.itineraryUpdateTrigger++;
        },

        removeResource(dayIndex, resourceType, itemIndex = null) {
            if (dayIndex !== null && this.itinerary[dayIndex]) {
                if (resourceType === 'accommodation') {
                    this.itinerary[dayIndex].accommodation = null;
                } else if (resourceType === 'tours' && itemIndex !== null) {
                    this.itinerary[dayIndex].tours.splice(itemIndex, 1);
                } else if (resourceType === 'transport') {
                    this.itinerary[dayIndex].transport = null;
                }
                // Trigger reactivity by creating a new array reference
                this.itinerary = this.itinerary.slice();
                this.itineraryUpdateTrigger++;
            }
        },

        // Resources Step Methods (Step 4)
        resourceSubstep: 0,
        selectedDayIds: [],
        selectedCityFilter: null,
        expandedCity: null,
        bulkResourceType: 'accommodation',
        bulkApplyStrategy: 'all',
        bulkHotelSearch: '',
        bulkHotelResults: [],
        bulkHotelSearching: false,
        bulkHotelActiveIndex: 0,
        searchTimer: null,
        selectedBulkHotel: null,
        bulkTourSearch: '',
        bulkTourResults: [],
        selectedBulkTour: null,
        selectedBulkTransport: null,
        bulkTransportType: 'private-car',
        bulkTransportFrom: '',
        bulkTransportTo: '',
        bulkTransportNote: '',
        bulkTransportSearch: '',
        bulkTransportResults: [],
        bulkTransportSearching: false,
        transportationTypes: [],
        loadedTransportationTypes: false,
        // Google Places hotel search state
        googleScriptLoaded: false,
        hotelSearchResults: [],
        showHotelModal: false,
        showHotelPreviewModal: false,
        previewHotel: null,
        showPreviewList: true,
        showFineTune: true,
        fineTuneActiveDay: undefined,
        modalHotel: {
            place_id: '', name: '', address: '', city: '', country: '', latitude: null, longitude: null,
            currency: 'USD', base_price_per_night: 0, tax_rate: 0, extra_bed_price: null, meal_plan: '', room_types: []
        },

        initResourcesStep() {
            this.buildItinerary();
            if (!this.itinerary.length) {
                console.warn('Resources init skipped: empty itinerary', {
                    start_date: this.form.start_date,
                    totalNights: this.totalNights
                });
                this.selectedDayIds = [];
                return;
            }
            this.selectedDayIds = this.itinerary.map((_, idx) => idx);
            this.selectedCityFilter = null;
            // Open and select first city by default
            const firstCity = this.getCitiesInItinerary()[0];
            if (firstCity) {
                this.expandedCity = firstCity.name;
                this.selectCityDays(firstCity.name);
            }
            this.resourceSubstep = 0;
            this.setTransportDefaults();
            this.loadTransportationTypes(); // Load available transportation types
            this.loadGoogleScript();
        },

        // Load available transportation types for this tenant
        async loadTransportationTypes() {
            if (this.loadedTransportationTypes) return;
            
            try {
                const response = await fetch(`${window.appConfig?.apiUrl}/v1/transportation-types`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Tenant-ID': window.appConfig?.tenantId || document.querySelector('meta[name="tenant-id"]')?.content || '',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    credentials: 'include'
                });
                
                const data = await response.json();
                if (data.success) {
                    this.transportationTypes = data.data || [];
                    this.loadedTransportationTypes = true;
                    console.log('Loaded transportation types:', this.transportationTypes);
                    
                    // Set default type if available
                    if (this.transportationTypes.length > 0 && !this.bulkTransportType) {
                        this.bulkTransportType = this.transportationTypes[0].slug;
                    }
                } else {
                    console.warn('Failed to load transportation types:', data.message);
                }
            } catch (error) {
                console.error('Error loading transportation types:', error);
            }
        },

        // Safe no-op for Google script; backend combined API powers search
        loadGoogleScript() {
            // Intentionally blank to avoid runtime errors when script is not needed
        },

        setTransportDefaults() {
            const firstSelected = this.itinerary[this.selectedDayIds[0]] || this.itinerary[0];
            this.bulkTransportFrom = firstSelected?.city || '';
            this.bulkTransportTo = firstSelected?.nextCity || firstSelected?.city || '';
        },

        // Resource day selection
        selectAllDays() {
            this.selectedDayIds = this.itinerary.map((_, idx) => idx);
        },
        deselectAllDays() {
            this.selectedDayIds = [];
        },
        selectSingleDay(idx) {
            this.selectedDayIds = [idx];
        },
        selectCityDays(cityName) {
            this.selectedDayIds = this.itinerary
                .map((day, idx) => ({ day, idx }))
                .filter(x => x.day.city === cityName)
                .map(x => x.idx);
        },
        editCityOnly(cityName) {
            // Enter edit mode: expand city, deselect all, select only this city's days, refresh searches
            this.expandedCity = cityName;
            this.deselectAllDays();
            this.selectCityDays(cityName);
            this.refreshResourceSearch(cityName);
        },
        toggleCity(cityName) {
            const isSame = this.expandedCity === cityName;
            this.expandedCity = isSame ? null : cityName;
            if (!isSame) {
                this.selectCityDays(cityName);
                this.refreshResourceSearch(cityName);
            } else {
                this.deselectAllDays();
                this.refreshResourceSearch(null);
            }
        },
        toggleDaySelection(dayIndex) {
            const idx = this.selectedDayIds.indexOf(dayIndex);
            if (idx > -1) {
                this.selectedDayIds.splice(idx, 1);
            } else {
                this.selectedDayIds.push(dayIndex);
            }
        },
        isSelectedDay(dayIndex) {
            return this.selectedDayIds.includes(dayIndex);
        },
        getCitiesInItinerary() {
            // Force reactivity by accessing trigger (unused but triggers dependency tracking)
            void this.itineraryUpdateTrigger;
            const uniqueCities = new Map();
            this.itinerary.forEach(day => {
                if (day.city && !uniqueCities.has(day.city)) {
                    uniqueCities.set(day.city, { id: day.city, name: day.city });
                }
            });
            return Array.from(uniqueCities.values());
        },
        getFilteredDays() {
            if (this.selectedCityFilter === null) return this.itinerary;
            return this.itinerary.filter(day => day.city === this.selectedCityFilter);
        },
        getCityDaysCount(cityName) {
            return this.itinerary.filter(day => day.city === cityName).length;
        },
        getCitySelectedDaysCount(cityName) {
            return this.selectedDayIds.filter(dayIdx => this.itinerary[dayIdx]?.city === cityName).length;
        },
        getDaysForCity(cityName) {
            // Force reactivity by accessing trigger
            void this.itineraryUpdateTrigger;
            // Return days with their itinerary indices for proper identification
            return this.itinerary
                .map((day, index) => ({ ...day, itineraryIndex: index }))
                .filter(day => day.city === cityName);
        },
        getItineraryWithCityHeaders() {
            // Force reactivity by accessing trigger
            void this.itineraryUpdateTrigger;
            // Flat structure: [{type: 'city', name: 'Adana'}, {type: 'day', data: {...}, index: 0}, ...]
            const result = [];
            const cities = this.getCitiesInItinerary();
            
            cities.forEach(city => {
                result.push({ type: 'city', name: city.name });
                this.itinerary.forEach((day, index) => {
                    if (day.city === city.name) {
                        result.push({ type: 'day', data: day, index: index });
                    }
                });
            });
            
            return result;
        },
        getItineraryIndex(day) {
            return this.itinerary.findIndex(d => d.id === day.id);
        },
        selectAllDaysInExpandedCity() {
            if (!this.expandedCity) return;
            this.selectCityDays(this.expandedCity);
        },
        scrollResourcesIntoView() {
            // Scroll to the resources section at the top
            setTimeout(() => {
                const resourcesSection = document.querySelector('[x-show="currentStep === 3"]');
                if (resourcesSection) {
                    resourcesSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        },
        editResourceDay(dayIndex) {
            // Select only this day (same as sidebar edit button)
            this.selectSingleDay(dayIndex);
            
            // Scroll to resources/sidebar at top
            this.scrollResourcesIntoView();
        },
        closeResourcesEditor() {
            this.fineTuneActiveDay = undefined;
        },
        openDayDetailsModal(dayIndex) {
            this.dayDetailsIndex = dayIndex;
            this.dayDetailsModal = true;
        },
        closeDayDetailsModal() {
            this.dayDetailsModal = false;
            this.dayDetailsIndex = null;
        },
        countDaysWithResource(resourceType) {
            return this.itinerary.filter(day => {
                if (resourceType === 'accommodation') return day.accommodation && !day.isLastNight;
                else if (resourceType === 'tours') return day.tours && day.tours.length > 0;
                else if (resourceType === 'transport') return day.transport;
                return false;
            }).length;
        },

        // Hotel search by city
        async searchBulkHotels() {
            if (this.bulkHotelSearch.length < 2) {
                this.bulkHotelResults = [];
                return;
            }
            this.bulkHotelSearching = true;
            const citiesInSelection = new Set(this.selectedDayIds.map(idx => this.itinerary[idx]?.city).filter(Boolean));
            try {
                const cityName = Array.from(citiesInSelection)[0];
                const response = await fetch(
                    `${window.appConfig?.apiUrl}/v1/resources/hotels?search=${encodeURIComponent(this.bulkHotelSearch)}&city=${encodeURIComponent(cityName)}`,
                    {
                        headers: {
                            'Accept': 'application/json',
                            'X-Tenant-ID': window.appConfig?.tenantId || '',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'include'
                    }
                );
                const data = await response.json();
                if (data.success && data.data) {
                    this.bulkHotelResults = Array.isArray(data.data) ? data.data : data.data.hotels || [];
                } else {
                    this.bulkHotelResults = [];
                }
            } catch (error) {
                console.error('Failed to search hotels:', error);
                this.bulkHotelResults = [];
            } finally {
                this.bulkHotelSearching = false;
            }
        },

        // Combined search via backend Google Maps service
        async searchBulkHotelsCombined(force = false) {
            const term = this.bulkHotelSearch || '';
            if (!force && term.length < 1) { this.bulkHotelResults = []; this.bulkHotelSearching = false; return; }
            let cityName = '';
            const citiesInSelection = new Set(this.selectedDayIds.map(idx => this.itinerary[idx]?.city).filter(Boolean));
            if (citiesInSelection.size > 0) {
                cityName = Array.from(citiesInSelection)[0];
            } else if (this.itinerary.length > 0) {
                cityName = this.itinerary[0]?.city || '';
            }
            if (!cityName) { 
                this.bulkHotelResults = []; 
                this.bulkHotelSearching = false; 
                return; 
            }
            try {
                const apiUrl = window.appConfig?.apiUrl;
                this.bulkHotelSearching = true;
                const resp = await fetch(`${apiUrl}/v1/resources/hotels/combined?search=${encodeURIComponent(term)}&city=${encodeURIComponent(cityName)}`,
                    { headers: { 'Accept': 'application/json', 'X-Tenant-ID': window.appConfig?.tenantId || '' }, credentials: 'include' }
                );
                const data = await resp.json();
                this.bulkHotelResults = Array.isArray(data.data) ? data.data : [];
                this.bulkHotelActiveIndex = 0;
            } catch (e) {
                console.error('Combined hotel search failed', e);
                this.bulkHotelResults = [];
            } finally { this.bulkHotelSearching = false; }
        },
        debouncedSearchBulkHotelsCombined() {
            clearTimeout(this.searchTimer);
            this.searchTimer = setTimeout(() => this.searchBulkHotelsCombined(false), 150);
        },
        openHotelPreview(hotel) {
            // Validate that selected days are all in the same city
            if (this.selectedDayIds.length === 0) {
                alert('Please select at least one day to assign a hotel');
                return;
            }
            const citiesInSelection = new Set(this.selectedDayIds.map(idx => this.itinerary[idx]?.city).filter(Boolean));
            if (citiesInSelection.size > 1) {
                alert('Please select days from only one city to assign a hotel');
                return;
            }
            
            this.previewHotel = hotel;
            const selectedCity = Array.from(citiesInSelection)[0];
            const tenantCurrency = window.appConfig?.tenantCurrency || 'USD';
            
            // Initialize modalHotel with auto-populated city and country
            this.modalHotel = {
                place_id: hotel.place_id || '',
                name: hotel.name || '',
                address: hotel.address || '',
                city: selectedCity || hotel.city || '',
                country: this.form.client_country || hotel.country || '', // Use offer's client_country first
                latitude: hotel.latitude || null,
                longitude: hotel.longitude || null,
                // For new hotels: use tenant currency
                currency: tenantCurrency,
                base_price_per_night: hotel.base_price_per_night ? Number(hotel.base_price_per_night) : null,
                tax_rate: hotel.tax_rate ? Number(hotel.tax_rate) : 0,
                extra_bed_price: hotel.extra_bed_price ? Number(hotel.extra_bed_price) : null,
                meal_plan: hotel.meal_plan || '',
                room_types: hotel.room_types || []
            };
            this.showHotelPreviewModal = true;
        },
        closeHotelPreview() { this.showHotelPreviewModal = false; this.previewHotel = null; },
        assignExistingHotelFromPreview() {
            if (!this.previewHotel || !this.previewHotel.exists) return;
            this.selectedBulkHotel = {
                id: this.previewHotel.id,
                name: this.previewHotel.name,
                stars: this.previewHotel.stars || 0,
                base_price_per_night: this.previewHotel.base_price_per_night || 0,
                address: this.previewHotel.address,
            };
            this.applyHotelBulk();
            this.closeHotelPreview();
        },
        continueAddNewHotel() {
            if (!this.previewHotel) return;
            this.openHotelModal(this.previewHotel);
            this.closeHotelPreview();
        },
        quickAssignHotel(hotel) {
            const cities = new Set(this.selectedDayIds.map(idx => this.itinerary[idx]?.city).filter(Boolean));
            if (cities.size > 1) {
                alert('Select days from the same city to assign.');
                return;
            }
            // Build selectedBulkHotel and apply
            this.selectedBulkHotel = {
                id: hotel.id,
                name: hotel.name,
                stars: hotel.stars || 0,
                base_price_per_night: hotel.base_price_per_night || 0,
                address: hotel.address
            };
            this.applyHotelBulk();
        },
        openHotelModal(hotel) {
            const tenantCurrency = window.appConfig?.tenantCurrency || 'USD';
            this.modalHotel = {
                ...hotel,
                currency: tenantCurrency, base_price_per_night: 0, tax_rate: 0, extra_bed_price: null, meal_plan: '', room_types: []
            };
            this.showHotelModal = true;
        },
        closeHotelModal() { this.showHotelModal = false; },
        clearGoogleHotelSearch() {
            this.bulkHotelSearch = '';
            this.bulkHotelResults = [];
            this.bulkHotelSearching = false;
            this.hotelSearchResults = [];
        },
        async confirmAddHotel() {
            // Validate required fields
            if (!this.modalHotel.country?.trim()) {
                alert('Country is required');
                return;
            }
            if (!this.modalHotel.currency?.trim()) {
                alert('Currency is required');
                return;
            }
            if (!this.modalHotel.base_price_per_night || Number(this.modalHotel.base_price_per_night) <= 0) {
                alert('Base Price per Night must be greater than 0');
                return;
            }

            const apiUrl = window.appConfig?.apiUrl;
            const tenantId = window.appConfig?.tenantId || '';
            
            try {
                const resp = await fetch(`${apiUrl}/v1/tenant-hotels`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-Tenant-ID': tenantId,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    credentials: 'include',
                    body: JSON.stringify(this.modalHotel)
                });
                const data = await resp.json();
                if (!resp.ok || !data.success) { 
                    alert(data.message || 'Failed to add hotel'); 
                    return; 
                }
                // Assign accommodation to selected days
                this.selectedDayIds.forEach(dayIdx => {
                    const day = this.itinerary[dayIdx];
                    day.accommodation = data.data.hotel;
                });
                // Trigger reactivity synchronously
                this.itinerary = this.itinerary.slice();
                this.itineraryUpdateTrigger++;
                this.closeHotelPreview();
                this.closeHotelModal();
                this.clearGoogleHotelSearch();
            } catch (error) {
                console.error('Error adding hotel:', error);
                alert('An error occurred while adding the hotel');
            }
        },

        // Tour search by city
        async searchBulkTours(force = false) {
            const term = this.bulkTourSearch || '';
            if (!force && term.length < 1) {
                this.bulkTourResults = [];
                return;
            }
            const citiesInSelection = new Set(this.selectedDayIds.map(idx => this.itinerary[idx]?.city).filter(Boolean));
            try {
                const cityName = Array.from(citiesInSelection)[0];
                const response = await fetch(
                    `${window.appConfig?.apiUrl}/v1/resources/tours?search=${encodeURIComponent(term)}&city=${encodeURIComponent(cityName)}`,
                    {
                        headers: {
                            'Accept': 'application/json',
                            'X-Tenant-ID': window.appConfig?.tenantId || '',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'include'
                    }
                );
                const data = await response.json();
                if (data.success && data.data) {
                    this.bulkTourResults = Array.isArray(data.data) ? data.data : data.data.tours || [];
                } else {
                    this.bulkTourResults = [];
                }
            } catch (error) {
                console.error('Failed to search tours:', error);
                this.bulkTourResults = [];
            }
        },

        // Transport search by database
        async searchBulkTransport(force = false) {
            const term = this.bulkTransportSearch || '';
            if (!force && term.length < 1) {
                this.bulkTransportResults = [];
                return;
            }
            const citiesInSelection = new Set(this.selectedDayIds.map(idx => this.itinerary[idx]?.city).filter(Boolean));
            try {
                const cityName = Array.from(citiesInSelection)[0] || '';
                this.bulkTransportSearching = true;
                const response = await fetch(
                    `${window.appConfig?.apiUrl}/v1/resources/transport?search=${encodeURIComponent(term)}&city=${encodeURIComponent(cityName)}`,
                    {
                        headers: {
                            'Accept': 'application/json',
                            'X-Tenant-ID': window.appConfig?.tenantId || '',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'include'
                    }
                );
                const data = await response.json();
                if (data.success && data.data) {
                    this.bulkTransportResults = Array.isArray(data.data) ? data.data : data.data.transport || [];
                } else {
                    this.bulkTransportResults = [];
                }
            } catch (error) {
                console.error('Failed to search transport:', error);
                this.bulkTransportResults = [];
            } finally {
                this.bulkTransportSearching = false;
            }
        },

        // Apply bulk resources
        applyBulkResources() {
            const cities = new Set(this.selectedDayIds.map(idx => this.itinerary[idx]?.city).filter(Boolean));
            if (cities.size > 1) {
                alert('Please select days from the same city to apply bulk resources.');
                return;
            }
            if (this.bulkResourceType === 'accommodation' && this.selectedBulkHotel) {
                this.applyHotelBulk();
            } else if (this.bulkResourceType === 'tours' && this.selectedBulkTour) {
                this.applyTourBulk();
            } else if (this.bulkResourceType === 'transport') {
                this.applyTransportBulk();
            }
            this.resourceSubstep = 2;
        },
        applyHotelBulk() {
            const strategy = this.bulkApplyStrategy;
            let assignedCount = 0;
            this.selectedDayIds.forEach(dayIdx => {
                const day = this.itinerary[dayIdx];
                if (strategy === 'exclude-last-night' && day.isLastNight) return;
                if (!day.accommodation || strategy === 'all' || strategy === 'by-city') {
                    day.accommodation = {
                        id: this.selectedBulkHotel.id,
                        name: this.selectedBulkHotel.name,
                        stars: this.selectedBulkHotel.stars,
                        base_price_per_night: this.selectedBulkHotel.base_price_per_night,
                        address: this.selectedBulkHotel.address
                    };
                    assignedCount++;
                }
            });
            // Trigger reactivity - FORCE complete re-render with ALL nested objects
            const newItinerary = this.itinerary.map(day => ({
                ...day, 
                tours: [...(day.tours || [])],
                accommodation: day.accommodation ? {...day.accommodation} : null,
                transport: day.transport ? {...day.transport} : null
            }));
            this.itinerary = newItinerary;
            this.itineraryUpdateTrigger++;
            // Hotel assigned successfully
        },
        applyTourBulk() {
            const strategy = this.bulkApplyStrategy;
            let assignedCount = 0;
            this.selectedDayIds.forEach((dayIdx, idx) => {
                if (strategy === 'alternating-tours' && idx % 2 !== 0) return;
                const day = this.itinerary[dayIdx];
                if (!day.tours) day.tours = [];
                const exists = day.tours.some(t => String(t.id) === String(this.selectedBulkTour.id));
                if (!exists) {
                    day.tours.push({
                        id: this.selectedBulkTour.id,
                        name: this.selectedBulkTour.name,
                        duration: this.selectedBulkTour.duration,
                        price_per_person: this.selectedBulkTour.price_per_person
                    });
                    assignedCount++;
                }
            });
            // Trigger reactivity - FORCE complete re-render with ALL nested objects
            const newItinerary = this.itinerary.map(day => ({
                ...day, 
                tours: [...(day.tours || [])],
                accommodation: day.accommodation ? {...day.accommodation} : null,
                transport: day.transport ? {...day.transport} : null
            }));
            this.itinerary = newItinerary;
            this.itineraryUpdateTrigger++;
            // Tour assigned successfully
        },

        applyTransportBulk() {
            if (!this.selectedBulkTransport) {
                alert('Please choose a transport option from your resources');
                return;
            }
            if (!this.selectedDayIds.length) {
                if (this.expandedCity) this.selectCityDays(this.expandedCity); else this.selectAllDays();
            }
            const transport = this.selectedBulkTransport;
            this.selectedDayIds.forEach(dayIdx => {
                const day = this.itinerary[dayIdx];
                day.transport = {
                    id: transport.id,
                    name: transport.name,
                    vehicle_type: transport.vehicle_type || transport.mode || '',
                    capacity: transport.capacity ?? null,
                    luggage_capacity: transport.luggage_capacity ?? null,
                    daily_rate: transport.daily_rate ?? transport.price_per_day ?? null,
                    from: day.city || '',
                    to: day.nextCity || day.city || '',
                    note: transport.note || transport.description || transport.name || ''
                };
            });
            const newItinerary = this.itinerary.map(day => ({
                ...day, 
                tours: [...(day.tours || [])], 
                transport: day.transport ? {...day.transport} : null,
                accommodation: day.accommodation ? {...day.accommodation} : null
            }));
            this.itinerary = newItinerary;
            this.itineraryUpdateTrigger++;
        },

        // Direct-assign helpers for consistent UI across tabs
        assignHotelToSelectedDays(hotel) {
            if (!hotel) {
                console.warn('No hotel provided');
                return false;
            }
            if (!this.selectedDayIds.length) {
                console.warn('No days selected');
                alert('Please select at least one day');
                return false;
            }

            // Always use Google (combined) results; if the hotel is not in tenant catalog, open preview to add it before assigning
            if (!hotel.tenant_has) {
                this.openHotelPreview(hotel);
                return false;
            }
            
            this.selectedBulkHotel = {
                id: hotel.id,
                name: hotel.name,
                stars: hotel.stars || 0,
                base_price_per_night: hotel.base_price_per_night || 0,
                address: hotel.address
            };
            this.applyHotelBulk();
            return true;
        },
        assignTourToSelectedDays(tour) {
            if (!tour) {
                console.warn('No tour provided');
                return false;
            }
            if (!this.selectedDayIds.length) {
                console.warn('No days selected');
                alert('Please select at least one day');
                return false;
            }
            
            this.selectedBulkTour = {
                id: tour.id,
                name: tour.name,
                duration: tour.duration,
                price_per_person: tour.price_per_person
            };
            this.applyTourBulk();
            return true;
        },

        assignTransportToSelectedDays(transport) {
            if (!transport) {
                console.warn('No transport provided');
                return false;
            }
            if (!this.selectedDayIds.length) {
                alert('Please select at least one day');
                return false;
            }
            this.selectedBulkTransport = transport;
            this.applyTransportBulk();
            return true;
        },

        isHotelAssigned(hotel) {
            if (!hotel || !this.selectedDayIds.length) return false;
            return this.selectedDayIds.every(idx => {
                const acc = this.itinerary[idx]?.accommodation;
                return acc && String(acc.id) === String(hotel.id);
            });
        },

        isTourAssigned(tour) {
            if (!tour || !this.selectedDayIds.length) return false;
            return this.selectedDayIds.every(idx => {
                const tours = this.itinerary[idx]?.tours || [];
                return tours.some(t => String(t.id) === String(tour.id));
            });
        },

        isTransportAssigned(transport) {
            if (!transport || !this.selectedDayIds.length) return false;
            return this.selectedDayIds.every(idx => {
                const trans = this.itinerary[idx]?.transport;
                return trans && String(trans.id) === String(transport.id);
            });
        },

        getTransportFit(transport) {
            const total = this.totalTravelers || 0;
            const capacity = Number(transport?.capacity || 0);
            if (!total) return { label: 'Info', tone: 'info', detail: 'Add traveler count to get a recommendation' };
            if (!capacity) return { label: 'Unknown', tone: 'neutral', detail: 'Capacity not provided' };
            if (capacity >= total) return { label: 'Good fit', tone: 'success', detail: `${capacity} seats for ${total} travelers` };
            const vehiclesNeeded = Math.ceil(total / capacity);
            return {
                label: `Needs ${vehiclesNeeded} vehicles`,
                tone: 'warning',
                detail: `${capacity} seats per vehicle for ${total} travelers`
            };
        },

        getTourFit(tour) {
            const total = this.totalTravelers || 0;
            const capacity = Number(tour?.capacity || 0);
            if (!total) return { label: 'Info', tone: 'info', detail: 'Add traveler count to get a recommendation' };
            if (!capacity) return { label: 'Unknown', tone: 'neutral', detail: 'Capacity not provided' };
            if (capacity >= total) return { label: 'Good fit', tone: 'success', detail: `${capacity} spots for ${total} travelers` };
            return {
                label: 'May split groups',
                tone: 'warning',
                detail: `${capacity} spots available vs ${total} travelers`
            };
        },

        refreshResourceSearch(cityName) {
            this.bulkHotelSearch = '';
            this.bulkHotelResults = [];
            this.bulkTourSearch = '';
            this.bulkTourResults = [];
            this.bulkTransportSearch = '';
            this.bulkTransportResults = [];
            this.selectedBulkHotel = null;
            this.selectedBulkTour = null;
            this.selectedBulkTransport = null;
            if (cityName) {
                this.searchBulkHotelsCombined(true);
                this.searchBulkTours(true);
                this.searchBulkTransport(true);
            }
        },

        // Per-day resource management
        copyDayResources(fromDayIdx, toDayIdx) {
            const fromDay = this.itinerary[fromDayIdx];
            const toDay = this.itinerary[toDayIdx];
            if (fromDay.accommodation) toDay.accommodation = { ...fromDay.accommodation };
            if (fromDay.tours && fromDay.tours.length > 0) toDay.tours = fromDay.tours.map(t => ({ ...t }));
            // Trigger reactivity synchronously
            this.itinerary = this.itinerary.slice();
            this.itineraryUpdateTrigger++;
            console.log('Copied resources from day', fromDayIdx, 'to day', toDayIdx);
        },
        repeatForSimilarCity(dayIndex) {
            const sourceDay = this.itinerary[dayIndex];
            const cityName = sourceDay.city;
            this.itinerary.forEach((day, idx) => {
                if (day.city === cityName && idx !== dayIndex) {
                    if (sourceDay.accommodation) day.accommodation = { ...sourceDay.accommodation };
                    if (sourceDay.tours && sourceDay.tours.length > 0) day.tours = sourceDay.tours.map(t => ({ ...t }));
                }
            });
            // Trigger reactivity synchronously
            this.itinerary = this.itinerary.slice();
            this.itineraryUpdateTrigger++;
            console.log('Repeated resources for all', cityName, 'days');
        },
        clearDayResources(dayIndex) {
            const day = this.itinerary[dayIndex];
            day.accommodation = null;
            day.tours = [];
            day.transport = null;
            // Trigger reactivity synchronously
            this.itinerary = this.itinerary.slice();
            this.itineraryUpdateTrigger++;
        },

        // Summary & validation
        getAccommodationCoverage() {
            const validDays = this.itinerary.filter(d => !d.isLastNight).length;
            const withHotel = this.itinerary.filter(d => d.accommodation && !d.isLastNight).length;
            return validDays > 0 ? Math.round((withHotel / validDays) * 100) : 0;
        },
        getAccommodationCoverageCount() {
            return this.itinerary.filter(d => d.accommodation && !d.isLastNight).length;
        },
        getActivityCoverage() {
            const total = this.itinerary.length;
            const withActivities = this.itinerary.filter(d => d.tours && d.tours.length > 0).length;
            return total > 0 ? Math.round((withActivities / total) * 100) : 0;
        },
        getActivityCoverageCount() {
            return this.itinerary.filter(d => d.tours && d.tours.length > 0).length;
        },
        getCitiesCovered() {
            return this.getCitiesInItinerary().length;
        },
        hasConflicts() {
            return this.hasAccommodationOnLastNight() || this.hasMissingHotelInMultiNightCities();
        },
        hasAccommodationOnLastNight() {
            const lastDay = this.itinerary[this.itinerary.length - 1];
            return lastDay && lastDay.accommodation;
        },
        getMissingActivityDays() {
            return this.itinerary.map((day, idx) => (!day.tours || day.tours.length === 0) ? idx : null).filter(idx => idx !== null);
        },
        hasMissingHotelInMultiNightCities() {
            const cityNights = {};
            this.itinerary.forEach((day, idx) => {
                if (!day.isLastNight) {
                    if (!cityNights[day.city]) cityNights[day.city] = { total: 0, withHotel: 0 };
                    cityNights[day.city].total++;
                    if (day.accommodation) cityNights[day.city].withHotel++;
                }
            });
            return Object.values(cityNights).some(city => city.total > 1 && city.withHotel < city.total);
        },
        fixLastNightAccommodation() {
            const lastDay = this.itinerary[this.itinerary.length - 1];
            if (lastDay) lastDay.accommodation = null;
        },
        completeResourceStep() {
            if (this.hasConflicts()) {
                alert('Please resolve conflicts before continuing');
                return;
            }
            this.currentStep = 4;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

            // Pricing Methods
            calculateTotals() {
                // Calculate per-category profit and margin
                Object.keys(this.pricing).forEach(category => {
                    const cat = this.pricing[category];
                    cat.profit = (cat.sale || 0) - (cat.purchase || 0);
                    cat.margin = cat.purchase > 0 ? ((cat.profit / cat.purchase) * 100) : 0;
                });
            },

            applyMarkup(percentage) {
                // Apply percentage markup to all categories
                Object.keys(this.pricing).forEach(category => {
                    const cat = this.pricing[category];
                    if (cat.purchase > 0) {
                        cat.sale = cat.purchase * (1 + percentage / 100);
                    }
                });
                this.calculateTotals();
            },

            optimizePricing() {
                // Smart pricing based on typical margins per category
                const margins = {
                    accommodation: 15,  // 15% typical for hotels
                    tours: 20,          // 20% for tours
                    transport: 18,      // 18% for transport
                    flights: 8,         // 8% for flights (lower margin)
                    addons: 25          // 25% for add-ons (highest)
                };

                Object.keys(this.pricing).forEach(category => {
                    const cat = this.pricing[category];
                    if (cat.purchase > 0) {
                        cat.sale = cat.purchase * (1 + margins[category] / 100);
                    }
                });
            
                this.calculateTotals();
            },

            formatCurrency(amount) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD',
                    minimumFractionDigits: 2
                }).format(amount || 0);
            },

            async saveDraft() {
            this.autoSaving = true;
            setTimeout(() => {
                this.autoSaving = false;
            }, 2000);
        }
    }
}

// Expose to Alpine x-data usage
if (typeof window !== 'undefined') {
    window.offerCreator = offerCreator;
}

// Initialize Flatpickr after page load
window.addEventListener('load', function() {
    console.log('Page loaded, initializing Flatpickr...');
    
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');
    const alpineEl = document.querySelector('[x-data="offerCreator()"]');
    
    if (startInput && alpineEl) {
        flatpickr('#start_date', {
            dateFormat: 'Y-m-d',
            minDate: 'today',
            onChange: function(selectedDates, dateStr) {
                console.log('Start date picker onChange fired:', dateStr);
                // Update Alpine data directly
                if (alpineEl.__x && alpineEl.__x.$data) {
                    alpineEl.__x.$data.form.start_date = dateStr;
                    alpineEl.__x.$data.calculateDuration();
                }
                startInput.value = dateStr;
                startInput.dispatchEvent(new Event('input', { bubbles: true }));
                startInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    }
    
    if (endInput && alpineEl) {
        flatpickr('#end_date', {
            dateFormat: 'Y-m-d',
            minDate: 'today',
            onChange: function(selectedDates, dateStr) {
                console.log('End date picker onChange fired:', dateStr);
                // Update Alpine data directly
                if (alpineEl.__x && alpineEl.__x.$data) {
                    alpineEl.__x.$data.form.end_date = dateStr;
                    alpineEl.__x.$data.calculateDuration();
                }
                endInput.value = dateStr;
                endInput.dispatchEvent(new Event('input', { bubbles: true }));
                endInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    }
});