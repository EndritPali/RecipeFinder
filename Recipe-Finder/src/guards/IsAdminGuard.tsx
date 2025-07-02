import { ReactNode } from "react";
import ForbiddenPage from "../Components/ui/ForbiddenPage";
import { useAuth } from '../context/AuthContext';
import { Spin, Layout } from 'antd';

export default function IsAdminGuard({ children }: { children: ReactNode }) {
    const auth = useAuth();
    const user = auth?.user;
    const isLoadingAuth = auth?.isLoadingAuth;

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