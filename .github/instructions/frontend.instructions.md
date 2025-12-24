---
applyTo:
  - "resources/**/*.{js,jsx,ts,tsx,vue}"
  - "resources/**/*.css"
  - "vite.config.js"
---

## Tailwind + Vite Frontend Requirements

- Use Vite with Tailwind 4; prefer TypeScript for components.
- Choose Vue 3 or React; keep components colocated by feature/module.
- Implement 3-color tenant branding via CSS variables exposed to Tailwind theme.
- Meet WCAG AA accessibility; provide ARIA, keyboard navigation, and focus states.
- Internationalization ready (en/ar at minimum); support RTL.
- Structure UI to match modules in `docs/ROADMAP.md` and keep routes under `/app/*`.

## SafarStep Brand Guidelines
- **Primary Color:** `#2A50BC` - Use for primary buttons, active states, navigation
- **Secondary Color:** `#10B981` - Use for success states, confirmations, positive feedback
- **Brand Gradients:** `linear-gradient(135deg, #2A50BC 0%, #1d4ed8 100%)`
- **Focus States:** 2px solid outline with primary color and 3px shadow (`box-shadow: 0 0 0 3px rgba(42, 80, 188, 0.1)`)
- **Hover Effects:** Darken primary to `#1d4ed8`, secondary to `#059669`
- **Logo Text:** 1.5rem, font-weight: bold, color: primary
- **Brand Logos:**
  - Vertical (for sidebars, auth pages): `public/assets/images/logo/vertical.svg`
  - Horizontal (for headers, navigation): `public/assets/images/logo/horizontal.svg`
- **CSS Variables:**
  ```css
  :root {
    --brand-primary: #2A50BC;
    --brand-secondary: #10B981;
    --brand-accent: var(--tenant-accent, #f59e0b);
  }
  ```

## State and Data
- Use Axios with an API client wrapper that injects tenant + auth headers.
- Use Pinia (Vue) or Zustand (React) for state; avoid global state for local concerns.
- Handle token refresh and 401 retries centrally.

## Components Library
- Build reusable components (header, stats, table, modal, form field) per roadmap.
- Provide skeleton loaders and proper error states; support responsive layouts.

## Testing and Quality
- Add unit tests for components; integration tests for flows.
- Lint/format per project defaults; keep bundle sizes small and code split where needed.
