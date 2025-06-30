import { useRoutes } from 'react-router-dom';
import MainLayout from '../layouts/MainLayout';
import DashboardLayout from '../layouts/Dashboard';
import ResetPassword from '../features/auth/Pages/ResetPassword';
import DashboardContent from '../features/admin/components/DashboardContent';
import IsAuthenticatedGuard from '../guards/IsAuthenticatedGuard';
import IsAdminGuard from '../guards/IsAdminGuard';

export default function Router() {
  const routes = useRoutes([
    {
      path: '/',
      element: <MainLayout />,
    },
    {
      path: '/reset-password',
      element: <ResetPassword />
    },
    {
      path: '/admin',
      element: (
        <IsAuthenticatedGuard>
          <DashboardLayout />
        </IsAuthenticatedGuard>
      ),
      children: [
        {
          index: true,
          element: <DashboardContent />,
        },
        {
          path: 'users',
          element: (
            <IsAdminGuard>
              <DashboardContent />
            </IsAdminGuard>
          ),
        },
      ]
    },
  ]);

  return routes;
}
