import ForbiddenPage from "../Components/ForbiddenPage";
import { useAuth } from '../context/AuthContext';
import { Spin, Layout } from 'antd';

export default function IsAdminGuard({ children }) {
    const { currentUser, isLoadingAuth } = useAuth();

    if (isLoadingAuth && !currentUser) {
        return (
            <Layout style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: '100%' }}>
                <Spin size="large" />
            </Layout>
        );
    }

    if (currentUser?.role !== 'Admin') {
        return <ForbiddenPage />;
    }

    return children;
}