module.exports = {
    purge: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/@protonemedia/inertiajs-tables-laravel-query-builder/**/*.{js,vue}"
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {
            colors: {
                primary: {
                    50: "#ffe9ed",
                    100: "#ffc9d0",
                    200: "#ef9396",
                    300: "#e5676d",
                    400: "#ef4049",
                    500: "#f4252d",
                    600: "#e5162c",
                    700: "#d30027",
                    800: "#c6001f",
                    900: "#b70013"
                },
                secondary: {
                    50: "#e7eaf3",
                    100: "#c3cae1",
                    200: "#9da7cd",
                    300: "#7786b8",
                    400: "#5b6baa",
                    500: "#3e529c",
                    600: "#384a93",
                    700: "#2f4187",
                    800: "#28377b",
                    900: "#1c2764"
                },
                tertiary: {
                    50: "#edf3f7",
                    100: "#d5e1e7",
                    200: "#bccdd6",
                    300: "#a1b8c4",
                    400: "#8ba6b5",
                    500: "#7695a7",
                    600: "#698594",
                    700: "#58707d",
                    800: "#4a5c67",
                    900: "#38464f"
                }
            }
        }
    },
    variants: {
        extend: {}
    },
    plugins: [require("@tailwindcss/forms"), require("@tailwindcss/typography")]
};
