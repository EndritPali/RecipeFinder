import '../Scss/CreateCommentModal.scss'
import { Modal, Avatar, Input, Form, message } from 'antd'
import { UserOutlined } from '@ant-design/icons';
import { useEffect, useState } from 'react';
import { useCommentMutations } from '../hooks/useCommentMutations';
import { useAuth } from '../context/AuthContext';

export default function CreateCommentModal({ open, onOk, onCancel, mode = 'create', comment = null }) {
    const isEdit = mode === 'edit';
    const [form] = Form.useForm();
    const [submitting, setSubmitting] = useState(false);
    const { addComment, editComment } = useCommentMutations();

    const { user } = useAuth();

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
                await editComment.mutateAsync({ id: comment.id, description: values.description });
            } else {
                await addComment.mutateAsync(values.description);
            }

            form.resetFields();
            onOk();
        } catch {
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