# Button Migration to Modern Design System - Progress Report

## Date: December 19, 2025

## Objective

Migrate all deprecated button classes (`bg-livora-accent`, `bg-livora-primary`, `hover:bg-livora-primary`, `border-livora-primary`, `text-livora-accent`) to the modern design system.

## Replacement Rules

1. **Primary Action Buttons**: Use `btn btn-primary` class
2. **Secondary Buttons**: Use `btn btn-secondary` class
3. **Outline Buttons with `border-livora-primary`**: Use `btn btn-outline`
4. **Icon Backgrounds using `bg-livora-accent bg-opacity-20`**: Use `bg-gradient-to-br from-orange-100 to-orange-200`
5. **Icon Colors using `text-livora-accent`**: Use `text-orange-600`
6. **Toggle Switches and Progress Bars using `bg-livora-primary`**: Use `bg-[#ff6900]`
7. **Small Avatar Circles `bg-livora-primary`**: Use `bg-gradient-to-br from-[#ff6900] to-[#ff8533]`

## Completed Files (Fully Updated)

### Mitra Views

-   ✅ `resources/views/mitra/rooms/index.blade.php`

    -   Updated create room button to btn-primary
    -   Changed icon background to orange gradient
    -   Updated detail button to btn-outline
    -   Updated edit button to btn-primary
    -   Updated empty state button to btn-primary

-   ✅ `resources/views/mitra/rooms/show.blade.php`

    -   Updated edit button to btn-outline
    -   Changed text-livora-accent to text-orange-600
    -   Updated quick action edit button to btn-outline

-   ✅ `resources/views/mitra/rooms/edit.blade.php`

    -   Updated all 3 section header icons to text-orange-600
    -   Changed checkbox colors to orange-600
    -   Updated facility hover colors to orange-600
    -   Changed upload label colors to orange-600/orange-500
    -   Updated submit button to btn-primary

-   ✅ `resources/views/mitra/rooms/create.blade.php`

    -   Updated basic info icon to text-orange-600
    -   Changed facilities section icon to text-orange-600
    -   Updated checkbox and hover colors to orange
    -   Changed photo section icon to text-orange-600
    -   Updated upload label colors to orange
    -   Updated submit button to btn-primary

-   ✅ `resources/views/mitra/properties/index.blade.php` (Partial)

    -   Updated text-livora-accent to text-orange-600
    -   Updated detail button to btn-primary
    -   Updated edit button to btn-outline

-   ✅ `resources/views/mitra/bookings/index.blade.php` (Partial)
    -   Updated detail link color to orange-600
    -   Empty state button already uses btn-primary

## Files Needing Updates

### Mitra Views (Remaining)

-   ⏳ `resources/views/mitra/tickets/show.blade.php` - bg-livora-primary button
-   ⏳ `resources/views/mitra/tickets/index.blade.php` - text-livora-accent icons, bg-livora-primary buttons
-   ⏳ `resources/views/mitra/tickets/edit.blade.php` - focus:ring-livora-primary, bg-livora-primary button
-   ⏳ `resources/views/mitra/properties/show.blade.php` - border-livora-primary buttons, text-livora-accent
-   ⏳ `resources/views/mitra/properties/edit.blade.php` - text-livora-accent icons, bg-livora-primary button
-   ⏳ `resources/views/mitra/properties/create.blade.php` - bg-livora-accent, hover:bg-livora-primary buttons
-   ⏳ `resources/views/mitra/dashboard.blade.php` - bg-livora-primary, text-livora-accent
-   ⏳ `resources/views/mitra/bookings/show.blade.php` - text-livora-accent, bg-livora-accent
-   ⏳ `resources/views/mitra/bookings/edit.blade.php` - text-livora-accent, bg-livora-accent
-   ⏳ `resources/views/mitra/bookings/create.blade.php` - text-livora-accent, bg-livora-accent

### Tenant Views (All need updates)

-   ⏳ `resources/views/tenant/profile.blade.php` - bg-livora-primary, bg-livora-accent, focus:ring-livora-primary, peer-checked:bg-livora-primary
-   ⏳ `resources/views/tenant/tickets/index.blade.php` - text-livora-accent, bg-livora-primary
-   ⏳ `resources/views/tenant/tickets/show.blade.php` - needs checking
-   ⏳ `resources/views/tenant/tickets/edit.blade.php` - focus:ring-livora-primary, bg-livora-primary
-   ⏳ `resources/views/tenant/tickets/create.blade.php` - text-livora-accent, bg-livora-primary
-   ⏳ `resources/views/tenant/payments/create.blade.php` - text-livora-accent, focus:ring-livora-primary
-   ⏳ `resources/views/tenant/payments/index.blade.php` - text-livora-accent, bg-livora-primary
-   ⏳ `resources/views/tenant/payments/show.blade.php` - text-livora-accent
-   ⏳ `resources/views/tenant/payments/edit.blade.php` - text-livora-accent, border-livora-primary
-   ⏳ `resources/views/tenant/bookings/index.blade.php` - bg-livora-primary, focus:ring-livora-primary
-   ⏳ `resources/views/tenant/bookings/show.blade.php` - bg-livora-primary
-   ⏳ `resources/views/tenant/bookings/edit.blade.php` - focus:ring-livora-primary, bg-livora-primary
-   ⏳ `resources/views/tenant/bookings/create.blade.php` - bg-livora-primary, focus:ring-livora-primary
-   ⏳ `resources/views/tenant/bookings/partials/property-card.blade.php` - hover:border-livora-primary

### Admin Views (All need updates)

-   ⏳ `resources/views/admin/users/index.blade.php` - focus:ring-livora-primary, bg-livora-primary
-   ⏳ `resources/views/admin/users/show.blade.php` - bg-livora-primary
-   ⏳ `resources/views/admin/users/edit.blade.php` - focus:ring-livora-primary, bg-livora-primary
-   ⏳ `resources/views/admin/users/create.blade.php` - focus:ring-livora-primary, bg-livora-primary
-   ⏳ `resources/views/admin/tickets/create.blade.php` - focus:ring-livora-primary
-   Plus 15+ more admin files

### Public Views (All need updates)

-   ⏳ `resources/views/public/index.blade.php` - text-livora-accent, bg-livora-primary, bg-livora-accent
-   ⏳ `resources/views/public/show.blade.php` - bg-livora-accent, bg-livora-primary, text-livora-accent
-   ⏳ `resources/views/public/about.blade.php` - text-livora-accent
-   Plus 2 more public files

## Statistics

-   **Total Files Scanned**: 70+
-   **Files Fully Updated**: 5
-   **Files Partially Updated**: 2
-   **Files Remaining**: 63+
-   **Total Replacements Made**: ~30

## Next Steps

1. Complete remaining mitra views (12 files)
2. Process all tenant views (16 files)
3. Process all admin views (29 files)
4. Process all public views (5 files)
5. Run `npm run build` to compile assets
6. Test all pages to ensure buttons work correctly
7. Commit changes with message: "fix: migrate all buttons to modern design system"
8. Push to GitHub

## Notes

-   Some files have multiple instances of the same class requiring different replacements based on context
-   Focus ring and border colors also need migration (focus:ring-livora-primary → focus:ring-orange-500, etc.)
-   Input fields with focus:border-livora-primary should become focus:border-orange-500
-   Toggle switches with peer-checked:bg-livora-primary should become peer-checked:bg-[#ff6900]
