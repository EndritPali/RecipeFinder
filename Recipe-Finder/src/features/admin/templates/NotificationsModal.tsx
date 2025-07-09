import { Modal, List, Avatar, Button, Spin } from "antd";
import { UserOutlined } from '@ant-design/icons';
import '../scss/NotificationsModal.scss';
import { useState, useEffect } from 'react';
import { useAuth } from "@/context/AuthContext";
import ApproveResetModal from "./ApproveResetModal";
import { NotificationsModalProps, ResetInfo } from '@/types/admin';
import { useFetchResetRequests } from "../hooks/useFetchRequests";
import { usePasswordResetEvents } from "../hooks/usePasswordResetEvents";
import useWindowResize from "@/lib/hooks/useWindowResize";

export default function NotificationsModal({ open, onOk, onCancel }: NotificationsModalProps) {
    const { isAuthenticated, user } = useAuth() as { isAuthenticated: boolean; user: { role?: string } | null };
    const [showInfoModal, setShowInfoModal] = useState(false);
    const [resetInfo, setResetInfo] = useState<ResetInfo | null>(null);
    const { isMobile } = useWindowResize();

    const isAdmin = user?.role === 'Admin';

    const {
        data: resetRequests,
        isLoading,
        refetch,
        approveOrDeny,
        isMutating,
    } = useFetchResetRequests(isAdmin);

    usePasswordResetEvents(async () => { await refetch(); }, isAdmin);

    useEffect(() => {
        if (open && isAuthenticated && isAdmin) {
            refetch();
        }
    }, [open, isAuthenticated, isAdmin, refetch]);

    const handleAction = async (resetId: string, action: 'approve' | 'deny') => {
        await approveOrDeny({ resetId, action });
    };

    const getInitials = (name?: string) => {
        if (!name) return '';
        const parts = name.trim().split(' ');
        if (parts.length === 1) return parts[0][0].toUpperCase();
        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    };

    return (
        <>
            {resetInfo && (
                <ApproveResetModal
                    open={showInfoModal}
                    onOk={() => setShowInfoModal(false)}
                    onCancel={() => setShowInfoModal(false)}
                    tempPassword={resetInfo.temporary_password}
                    resetEmail={resetInfo.user_email}
                />
            )}

            <Modal
                className="notifications-modal"
                open={open}
                onOk={onOk}
                onCancel={onCancel}
                title="Notifications Panel"
                footer={null}
            >
                {isLoading ? (
                    <div style={{ textAlign: 'center', padding: '20px' }}>
                        <Spin />
                    </div>
                ) : (
                    <List
                        itemLayout={isMobile ? 'vertical' : 'horizontal'}
                        dataSource={resetRequests}
                        locale={{ emptyText: 'No password reset requests' }}
                        renderItem={item => (
                            <List.Item>
                                <List.Item.Meta
                                    avatar={
                                        <Avatar>
                                            {item.username ? getInitials(item.username) : <UserOutlined />}
                                        </Avatar>
                                    }
                                    title={`Request from: ${item.email}`}
                                    description={`Last password remembered: ${item.last_password}`}
                                />
                                <div className="list-buttons">
                                    <Button type="primary" loading={isMutating} onClick={() => handleAction(item.id, 'approve')}>Accept</Button>
                                    <Button loading={isMutating} onClick={() => handleAction(item.id, 'deny')}>Deny</Button>
                                </div>
                            </List.Item>
                        )}
                    />
                )}
            </Modal>
        </>
    );
}