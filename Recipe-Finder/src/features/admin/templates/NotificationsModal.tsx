import { Modal, List, Avatar, Button, Spin, message } from "antd";
import { UserOutlined } from '@ant-design/icons';
import '../scss/NotificationsModal.scss';
import { useState, useEffect } from 'react';
import api from "@/lib/api/api";
import { useAuth } from "@/context/AuthContext";
import ApproveResetModal from "./ApproveResetModal";
import { NotificationsModalProps, ResetRequest, ResetInfo } from '@/types/admin';

export default function NotificationsModal({ open, onOk, onCancel }: NotificationsModalProps) {
    const [resetRequests, setResetRequests] = useState<ResetRequest[]>([]);
    const [loading, setLoading] = useState(false);
    const { isAuthenticated } = useAuth() as { isAuthenticated: boolean };
    const [showInfoModal, setShowInfoModal] = useState(false);
    const [resetInfo, setResetInfo] = useState<ResetInfo | null>(null);


    useEffect(() => {
        if (open && isAuthenticated) {
            fetchResetRequests();
        }
    }, [open, isAuthenticated]);

    const fetchResetRequests = async () => {
        setLoading(true);
        try {
            const response = await api.get('v1/auth/password-reset/pending');
            setResetRequests(response.data.data || []);
        } catch (error) {
            console.error('Error fetching reset requests:', error);
            message.error('Failed to load password reset requests');
        } finally {
            setLoading(false);
        }
    };

    const handleAction = async (resetId: string, action: 'approve' | 'deny') => {
        try {
            const response = await api.post('v1/auth/password-reset/process', {
                reset_id: resetId,
                action
            });

            if (action === 'approve') {
                const { temporary_password, user_email } = response.data;
                setResetInfo({ user_email, temporary_password });
                setShowInfoModal(true);
            } else {
                message.success('Password reset request denied');
            }

            fetchResetRequests();
        } catch (error) {
            console.error(`Error ${action}ing request:`, error);
            message.error(`Failed to ${action} password reset request`);
        }
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
                title="Notifications Panel (Password Reset)"
                footer={null}
            >
                {loading ? (
                    <div style={{ textAlign: 'center', padding: '20px' }}>
                        <Spin />
                    </div>
                ) : (
                    <List
                        itemLayout="horizontal"
                        dataSource={resetRequests}
                        locale={{ emptyText: 'No password reset requests' }}
                        renderItem={item => (
                            <List.Item>
                                <List.Item.Meta
                                    avatar={<Avatar icon={<UserOutlined />} />}
                                    title={`Request from: ${item.email}`}
                                    description={`Last password remembered: ${item.last_password}`}
                                />
                                <div className="list-buttons">
                                    <Button type="primary" onClick={() => handleAction(item.id, 'approve')}>Accept</Button>
                                    <Button onClick={() => handleAction(item.id, 'deny')}>Deny</Button>
                                </div>
                            </List.Item>
                        )}
                    />
                )}
            </Modal>
        </>
    );
}