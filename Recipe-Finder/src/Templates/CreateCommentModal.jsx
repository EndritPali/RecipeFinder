import '../Scss/CreateCommentModal.scss'
import { Modal, Avatar, Input, Form, message } from 'antd'
import { UserOutlined } from '@ant-design/icons';
import { useEffect, useState } from 'react';
import api from '../Services/api';
import auth from '../Services/auth';

export default function CreateCommentModal({ open, onOk, onCancel, refreshComments, mode = 'create', comment = null }) {
    const isEdit = mode === 'edit';
    const [form] = Form.useForm();
    const [user, setUser] = useState(null);
    const [submitting, setSubmitting] = useState(false);

    useEffect(() => {
        if (open) {
            auth.getCurrentUser().then(setUser);
        }
    }, [open]);

    useEffect(() => {
        if (open && isEdit && comment) {
            form.setFieldsValue({
                description: comment.comment
            });

        }
    }, [open, isEdit, comment, form]);


    const handleSubmit = async () => {
        try {
            const values = await form.validateFields();
            setSubmitting(true);

            if (isEdit && comment) {
                await api.put(`v1/comments/${comment.id}`, {
                    description: values.description,
                });
                message.success('Comment updated successfully!');
            } else {
                await api.post('v1/comments', {
                    description: values.description,
                });
                message.success('Comment added successfully!');
            }

            form.resetFields();
            refreshComments?.();
            onOk();
        } catch (err) {
            console.error('Submit error:', err);
            message.error(`Failed to ${isEdit ? 'edit' : 'add'} comment`);
        } finally {
            setSubmitting(false);
        }
    };

    return (
        <Modal
            className='create-comment'
            title={isEdit ? 'Edit comment' : 'Add new comment'}
            open={open}
            onOk={handleSubmit}
            onCancel={onCancel}
            confirmLoading={submitting}
        >
            <Form
                className='create-comment-form'
                layout='vertical'
                form={form}
            >
                <div className="create-comment-user-data">
                    <Avatar icon={<UserOutlined />} />
                    <h3>{user?.username || 'Unknown User'}</h3>
                </div>

                <Form.Item
                    className='create-comment-form-item'
                    name='description'
                    rules={[{ required: true, message: 'Comment cannot be empty' }]}
                >
                    <Input.TextArea
                        placeholder={isEdit ? 'Edit Comment' : 'Add new comment'}
                        rows={4}
                    />
                </Form.Item>

            </Form>

        </Modal>
    )
}