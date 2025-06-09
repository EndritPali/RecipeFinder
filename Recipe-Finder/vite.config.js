import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  optimizeDeps: {
    include: ['dayjs', 'dayjs/plugin/relativeTime']
  },
  build: {
    rollupOptions: {
      output: {
        manualChunks: {
          'react-vendor': ['react', 'react-dom', 'react-router-dom'],
          'antd-vendor': ['antd'],
          'utils-vendor': ['dayjs', 'lodash', 'axios']
        }
      }
    },
    chunkSizeWarningLimit: 1000
  }
})