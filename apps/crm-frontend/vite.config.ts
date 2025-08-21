import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [react()],
  server: {
    proxy: {
      '/auth': 'http://localhost:4000',
      '/internal': 'http://localhost:4000',
      '/files': 'http://localhost:4000'
    }
  }
});
