import { useRoutes } from 'react-router-dom';
import MainLayout from '../Layouts/MainLayout';
import DashboardLayout from '../Admin/Layouts/Dashboard';
import DashboardContent from '../Admin/Components/DashboardContent';
import IsAuthenticatedGuard from '../Guards/IsAuthenticatedGuard';
import IsAdminGuard from '../Guards/isAdminGuard';

export default function Router() {
  const routes = useRoutes([
    {
      path: '/',
      element: <MainLayout />,
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
