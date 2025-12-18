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
                // Livora Modern Brand Colors
                livora: {
                    50: "#fff7ed",
                    100: "#ffedd5",
                    200: "#fed7aa",
                    300: "#fdba74",
                    400: "#fb923c",
                    500: "#ff6900", // Primary
                    600: "#e55a00",
                    700: "#c2410c",
                    800: "#9a3412",
                    900: "#7c2d12",
                },
                // Legacy tiket theme (keep for backward compatibility)
                tiket: {
                    primary: "#FF6900",
                    secondary: "#FF8533",
                    dark: "#1A1A1A",
                    blue: "#0066CC",
                    success: "#00C851",
                    warning: "#FFBB33",
                    error: "#FF4444",
                    background: "#FAFAFA",
                    surface: "#FFFFFF",
                    border: "#E6E6E6",
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
                "glow-orange": "0 0 20px rgba(255, 105, 0, 0.4)",
                "glow-orange-lg": "0 0 30px rgba(255, 105, 0, 0.5)",
                "inner-sm": "inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)",
            },
            borderRadius: {
                tiket: "12px",
                "tiket-lg": "16px",
                "tiket-xl": "24px",
                "4xl": "2rem",
            },
            animation: {
                "fade-in": "fadeIn 0.3s ease-out",
                "slide-up": "slideUp 0.4s ease-out",
                "slide-down": "slideDown 0.4s ease-out",
                "scale-in": "scaleIn 0.3s ease-out",
                "bounce-subtle": "bounceSubtle 2s infinite",
                "pulse-slow": "pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite",
                "spin-slow": "spin 3s linear infinite",
            },
            keyframes: {
                fadeIn: {
                    "0%": { opacity: "0" },
                    "100%": { opacity: "1" },
                },
                slideUp: {
                    "0%": {
                        opacity: "0",
                        transform: "translateY(10px)",
                    },
                    "100%": {
                        opacity: "1",
                        transform: "translateY(0)",
                    },
                },
                slideDown: {
                    "0%": {
                        opacity: "0",
                        transform: "translateY(-10px)",
                    },
                    "100%": {
                        opacity: "1",
                        transform: "translateY(0)",
                    },
                },
                scaleIn: {
                    "0%": {
                        opacity: "0",
                        transform: "scale(0.95)",
                    },
                    "100%": {
                        opacity: "1",
                        transform: "scale(1)",
                    },
                },
                bounceSubtle: {
                    "0%, 100%": {
                        transform: "translateY(-5%)",
                        animationTimingFunction: "cubic-bezier(0.8, 0, 1, 1)",
                    },
                    "50%": {
                        transform: "translateY(0)",
                        animationTimingFunction: "cubic-bezier(0, 0, 0.2, 1)",
                    },
                },
            },
            backgroundImage: {
                "gradient-radial": "radial-gradient(var(--tw-gradient-stops))",
                "gradient-conic":
                    "conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))",
                "gradient-mesh":
                    "linear-gradient(135deg, rgba(255, 105, 0, 0.1) 0%, rgba(255, 133, 51, 0.1) 100%)",
            },
            backdropBlur: {
                xs: "2px",
            },
            transitionDuration: {
                400: "400ms",
            },
        },
    },

    plugins: [forms],
};
