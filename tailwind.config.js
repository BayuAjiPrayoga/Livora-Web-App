import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", "Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Tiket.com Inspired Theme
                tiket: {
                    primary: "#FF6900", // Orange - Primary brand color
                    secondary: "#FF8533", // Light orange - Secondary
                    dark: "#1A1A1A", // Dark text/backgrounds
                    blue: "#0066CC", // Accent blue
                    success: "#00C851", // Success green
                    warning: "#FFBB33", // Warning yellow
                    error: "#FF4444", // Error red
                    background: "#FAFAFA", // Light background
                    surface: "#FFFFFF", // Card/surface white
                    border: "#E6E6E6", // Light borders
                },
                gray: {
                    50: "#FAFAFA",
                    100: "#F5F5F5",
                    200: "#EEEEEE",
                    300: "#E0E0E0",
                    400: "#BDBDBD",
                    500: "#9E9E9E",
                    600: "#757575",
                    700: "#616161",
                    800: "#424242",
                    900: "#212121",
                },
            },
            boxShadow: {
                tiket: "0 2px 8px rgba(0, 0, 0, 0.1)",
                "tiket-md": "0 4px 16px rgba(0, 0, 0, 0.1)",
                "tiket-lg": "0 8px 32px rgba(0, 0, 0, 0.12)",
                "tiket-hover": "0 6px 20px rgba(0, 0, 0, 0.15)",
            },
            borderRadius: {
                tiket: "12px",
                "tiket-lg": "16px",
                "tiket-xl": "24px",
            },
            animation: {
                "fade-in": "fadeIn 0.5s ease-in-out",
                "slide-up": "slideUp 0.3s ease-out",
                "bounce-subtle": "bounceSubtle 2s infinite",
            },
            keyframes: {
                fadeIn: {
                    "0%": { opacity: "0" },
                    "100%": { opacity: "1" },
                },
                slideUp: {
                    "0%": { transform: "translateY(20px)", opacity: "0" },
                    "100%": { transform: "translateY(0)", opacity: "1" },
                },
                bounceSubtle: {
                    "0%, 100%": { transform: "translateY(0)" },
                    "50%": { transform: "translateY(-5px)" },
                },
            },
        },
    },

    plugins: [forms],
};
