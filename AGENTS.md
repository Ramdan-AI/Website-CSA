# Agents Responsive Design Conventions

1. Mobile-first design: use sm:, md:, lg:, xl: prefixes.
2. Touch targets: buttons and interactive elements must be minimum 44px x 44px (`min-h-[44px]`).
3. Responsive tables: use `overflow-x-auto` wrapper for all tables.
4. Dark mode: always use Tailwind CSS dark mode utilities, ensure contrast is accessible.
5. Theme switching: System theme is default, use `localStorage` for `theme`.
6. Testing:
   - Run `npm run lint` for styling checks.
   - Test on Chrome mobile simulator (iPhone 12/13/14/15/16 Pro).
