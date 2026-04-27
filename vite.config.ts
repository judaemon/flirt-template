import inertia from "@inertiajs/vite";
import { wayfinder } from "@laravel/vite-plugin-wayfinder";
import tailwindcss from "@tailwindcss/vite";
import react from "@vitejs/plugin-react";
import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";

export default defineConfig({
  plugins: [
    laravel({
      input: ["resources/css/app.css", "resources/js/app.tsx"],
      refresh: true,
    }),
    inertia(),
    react({
      babel: {
        plugins: ["babel-plugin-react-compiler"],
      },
    }),
    tailwindcss(),
    wayfinder({
      formVariants: true,
    }),
  ],
  server: {
    // host: "0.0.0.0",
    // port: 5173,
    // strictPort: true,
    // Allow CORS from specific dev origins.
    // Add your own local domain (or port) here if you're using a different setup.
    // Useful when running Vite behind a reverse proxy or using custom local domains.
    cors: {
      origin: ["http://stats-flow.local", "http://localhost:8000"],
      credentials: true,
    },
  },
});
