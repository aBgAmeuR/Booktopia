/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./assets/**/*.js", "./templates/**/*.html.twig"],
  theme: {
    container: {
      center: true,
      padding: "16px",
    },
    extend: {
      textColor: {
        primary: "var(--text-primary)",
        secondary: "var(--text-secondary)",
      },
      colors: {
        border: "var(--border))",
        input: "var(--input))",
        background: "var(--background))",
        primary: "var(--primary))",
        secondary: "var(--secondary))",
      },
      backgroundColor: {
        primary: "var(--primary)",
        secondary: "var(--secondary)",
        background: "var(--background)",
        input: "var(--input)",
      },
      borderRadius: {
        base: "var(--radius)",
      },
      fontSize: {
        sm: [
          "14px",
          {
            fontWeight: 400,
            lineHeight: "21px",
          },
        ],
        base: [
          "16px",
          {
            fontWeight: 500,
            lineHeight: "24px",
          },
        ],
        lg: [
          "18px",
          {
            fontWeight: 700,
            lineHeight: "22.5px",
            letterSpacing: "-0.27px",
          },
        ],
        xl: [
          "22px",
          {
            fontWeight: 700,
            lineHeight: "27.5px",
            letterSpacing: "-0.33px",
          },
        ],
        "2xl": [
          "32px",
          {
            fontWeight: 700,
            lineHeight: "40px",
            letterSpacing: "-0.8px",
          },
        ],
      },
    },
  },
  plugins: [],
};
