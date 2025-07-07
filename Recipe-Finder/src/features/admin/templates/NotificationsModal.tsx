import { Modal, List, Avatar, Button, Spin, message, Tabs } from "antd";
import { UserOutlined } from '@ant-design/icons';
import '../scss/NotificationsModal.scss';
import { useState, useEffect } from 'react';
import { useAuth } from "@/context/AuthContext";
import ApproveResetModal from "./ApproveResetModal";
import { NotificationsModalProps, ResetInfo } from '@/types/admin';
import { useCommentEvents } from "@/features/comments/hooks/useCommentEvents";
import { RecentComment } from "@/types/comment";
import { useFetchResetRequests } from "../hooks/useFetchRequests";
import { usePasswordResetEvents } from "../hooks/usePasswordResetEvents";

export default function NotificationsModal({ open, onOk, onCancel }: NotificationsModalProps) {
    const { isAuthenticated } = useAuth() as { isAuthenticated: boolean };
    const [showInfoModal, setShowInfoModal] = useState(false);
    const [resetInfo, setResetInfo] = useState<ResetInfo | null>(null);
    const [recentComments, setRecentComments] = useState<RecentComment[]>([]);
    const [commentsLoading, setCommentsLoading] = useState(false);

    const {
        resetRequests,
        isLoading,
        refetch,
        approveOrDeny,
        isMutating,
    } = useFetchResetRequests();

    usePasswordResetEvents(async () => { await refetch(); });

    useCommentEvents((comment: RecentComment) => {
        setRecentComments((prev: RecentComment[]) => [comment, ...prev].slice(0, 10));
    });

    useEffect(() => {
        if (open && isAuthenticated) {
            refetch();
            fetchRecentComments();
        }
    }, [open, isAuthenticated, refetch]);

    const fetchRecentComments = async () => {
        setCommentsLoading(true);
        try {
            setRecentComments([]);
        } finally {
            setCommentsLoading(false);
        }
    };

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
                <Tabs defaultActiveKey="1">
                    <Tabs.TabPane tab="Password Reset Requests" key="1">
                        {isLoading ? (
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
                    </Tabs.TabPane>
                    <Tabs.TabPane tab="Recent Comments" key="2">
                        {commentsLoading ? (
                            <div style={{ textAlign: 'center', padding: '20px' }}>
                                <Spin />
                            </div>
                        ) : (
                            <List
                                itemLayout="horizontal"
                                dataSource={recentComments}
                                locale={{ emptyText: 'No recent comments' }}
                                renderItem={(item: RecentComment) => (
                                    <List.Item>
                                        <List.Item.Meta
                                            avatar={
                                                <Avatar>
                                                    {item.user?.username ? getInitials(item.user.username) : <UserOutlined />}
                                                </Avatar>
                                            }
                                            title={item.user?.username || 'Unknown User'}
                                            description={item.description}
                                        />
                                        <div style={{ fontSize: 12, color: '#888' }}>{item.posted_at}</div>
                                    </List.Item>
                                )}
                            />
                        )}
                    </Tabs.TabPane>
                </Tabs>
            </Modal>
        </>
    );
}