import ForbiddenPage from "../Components/ForbiddenPage";
import { useAuth } from '../context/AuthContext';
import { Spin, Layout } from 'antd';

export default function IsAdminGuard({ children }) {
    const { user, isLoadingAuth } = useAuth();

    if (isLoadingAuth && !user) {
        return (
            <Layout style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: '100%' }}>
                <Spin size="large" />
            </Layout>
        );
    }

    if (user?.role !== 'Admin') {
        return <ForbiddenPage />;
    }

    return children;
}