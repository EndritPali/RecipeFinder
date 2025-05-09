import { Modal, Form, Input, Select } from 'antd';
import '../scss/UserModal.scss';
import { useEffect } from 'react';

import api from '../../Services/api';

export default function UserModal({ open, onOk, onCancel, mode = 'create', item }) {
    const isEdit = mode === 'edit';
    const [form] = Form.useForm();

    useEffect(() => {
        if (isEdit && item) {
            form.setFieldsValue({
                username: item.username,
                email: item.email,
                role: item.role
            });
        } else {
            form.resetFields();
        }
    }, [isEdit, item, form, open]);

    const handleFinish = async (values) => {
        try {
            const payload = {
                username: values.username,
                email: values.email,
                role: values.role,
                password: values.password,
            }

            if (isEdit) {
                await api.put(`/user/${item.key}`, payload);
            } else {
                await api.post('/user', payload);
            }

            onOk();
        } catch (error) {
            if (error.response && error.response.status === 500) {
                console.error("Validation errors:", error.response.data.errors);
            } else {
                console.error('Error saving user:', error);
            }
        }
    }

    return (
        <>
            <Modal
                className='create-user-modal'
                open={open}
                onOk={() => form.submit()}
                onCancel={() => {
                    form.resetFields();
                    onCancel();
                }}
                okText={isEdit ? 'Save changes' : 'Create user'}
                title={isEdit ? 'Edit User Information' : 'Create New User'}
            >
                <Form form={form} className='create-user-form' layout="vertical" onFinish={handleFinish}>
                    <Form.Item
                        className='user-form-item'
                        label='Username'
                        name="username"
                        rules={[{ required: true, message: 'Please input username!' }]}
                    >
                        <Input placeholder={isEdit ? 'Edit Username' : 'Username'} />
                    </Form.Item>

                    {isEdit ? (
                        <Form.Item
                            className='user-form-item'
                            label='Password'
                            name="password"
                        >
                            <Input.Password placeholder='Change password' />
                        </Form.Item>
                    ) : (
                        <Form.Item
                            className='user-form-item'
                            label='Password'
                            name="password"
                            rules={[{ required: !isEdit, message: 'Please input password!' }]}
                        >
                            <Input.Password placeholder='Enter password' />
                        </Form.Item>
                    )}

                    <Form.Item
                        className='user-form-item'
                        label='Email'
                        name="email"
                        rules={[
                            { required: true, message: 'Please input email!' },
                            { type: 'email', message: 'Please enter a valid email!' }
                        ]}
                    >
                        <Input placeholder={isEdit ? 'Edit email' : 'Enter email'} />
                    </Form.Item>

                    <Form.Item
                        className='user-form-item'
                        label='Role'
                        name="role"
                        rules={[{ required: true, message: 'Please select role!' }]}
                    >
                        <Select placeholder={isEdit ? 'Change role' : 'Enter role'}>
                            <Select.Option value='Admin'>Admin</Select.Option>
                            <Select.Option value='User'>User</Select.Option>
                        </Select>
                    </Form.Item>
                </Form>
            </Modal>
        </>
    );
}