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
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: "#152238",
                    light: "#1E2F4A",
                    dark: "#0E1828",
                },

                secondary: {
                    DEFAULT: "#2F5D8A",
                    light: "#4A7BB0",
                    dark: "#244766",
                },

                accent: {
                    DEFAULT: "#3AAFA9",
                    light: "#5FC7C1",
                    dark: "#2B8F8A",
                },

                success: "#22C55E",
                warning: "#F59E0B",
                danger: "#EF4444",

                background: "#F8FAFC",
                surface: "#FFFFFF",
                border: "#E5E7EB",
                muted: "#6B7280",
                text: "#111827",
            },
        },
    },

    plugins: [forms],
};
