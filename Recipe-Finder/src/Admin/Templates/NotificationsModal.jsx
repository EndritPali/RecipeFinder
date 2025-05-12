import { Modal, List, Avatar, Button, Spin, message } from "antd";
import { UserOutlined } from '@ant-design/icons';
import '../scss/NotificationsModal.scss';
import { useState, useEffect } from 'react';
import api from "../../Services/api";

export default function NotificationsModal({ open, onOk, onCancel }) {
    const [resetRequests, setResetRequests] = useState([]);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        if (open) {
            fetchResetRequests();
        }
    }, [open]);

    const fetchResetRequests = async () => {
        setLoading(true);
        try {
            const token = localStorage.getItem('token');
            if (!token) {
                message.error('Authentication required');
                return;
            }

            api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            const response = await api.get('/auth/password-reset/pending');
            setResetRequests(response.data.data || []);
        } catch (error) {
            console.error('Error fetching reset requests:', error);
            message.error('Failed to load password reset requests');
        } finally {
            setLoading(false);
        }
    };

    const handleAction = async (resetId, action) => {
        try {
            const token = localStorage.getItem('token');
            if (!token) {
                message.error('Authentication required');
                return;
            }

            api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            const response = await api.post('/auth/password-reset/process', {
                reset_id: resetId,
                action: action
            });

            if (action === 'approve') {
                const tempPassword = response.data.temporary_password;
                const userEmail = response.data.user_email;
                console.log(`Password Reset Approved\nEmail: ${userEmail}\nTemp Password: ${tempPassword}`);
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
                                <Button
                                    type="primary"
                                    onClick={() => handleAction(item.id, 'approve')}
                                >
                                    Accept
                                </Button>
                                <Button
                                    onClick={() => handleAction(item.id, 'deny')}
                                >
                                    Deny
                                </Button>
                            </div>
                        </List.Item>
                    )}
                />
            )}
        </Modal>
    );
}
