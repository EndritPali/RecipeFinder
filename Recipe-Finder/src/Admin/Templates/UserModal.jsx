import { Modal, Form, Input, Select } from 'antd';
import { useEffect } from 'react';
import api from '../../Services/api';
import '../scss/UserModal.scss';

export default function UserModal({ open, onOk, onCancel, mode = 'create', item }) {
    const isEdit = mode === 'edit';
    const [form] = Form.useForm();

    useEffect(() => {
        if (open) {
            if (isEdit && item) {
                form.setFieldsValue({
                    username: item.username,
                    email: item.email,
                    role: item.role
                });
            } else {
                form.resetFields();
            }
        }
    }, [form, isEdit, item, open]);

    const handleFinish = async (values) => {
        try {
            const payload = { ...values };

            if (isEdit) {
                await api.put(`api/v1/user/${item.key}`, payload);
            } else {
                await api.post('api/v1/user', payload);
            }

            onOk();
        } catch (error) {
            console.error('Error saving user:',
                error.response?.status === 500 ? error.response.data.errors : error);
        }
    };

    const formFields = [
        {
            name: 'username',
            label: 'Username',
            rules: [{ required: true, message: 'Please input username!' }],
            input: <Input placeholder={`${isEdit ? 'Edit' : 'Enter'} username`} />
        },
        {
            name: 'password',
            label: 'Password',
            rules: [{ required: !isEdit, message: 'Please input password!' }],
            input: <Input.Password placeholder={`${isEdit ? 'Change' : 'Enter'} password`} />
        },
        {
            name: 'email',
            label: 'Email',
            rules: [
                { required: true, message: 'Please input email!' },
                { type: 'email', message: 'Please enter a valid email!' }
            ],
            input: <Input placeholder={`${isEdit ? 'Edit' : 'Enter'} email`} />
        },
        {
            name: 'role',
            label: 'Role',
            rules: [{ required: true, message: 'Please select role!' }],
            input: (
                <Select placeholder={`${isEdit ? 'Change' : 'Select'} role`}>
                    <Select.Option value='Admin'>Admin</Select.Option>
                    <Select.Option value='User'>User</Select.Option>
                </Select>
            )
        }
    ];

    return (
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
                {formFields.map(field => (
                    <Form.Item
                        key={field.name}
                        className='user-form-item'
                        label={field.label}
                        name={field.name}
                        rules={field.rules}
                    >
                        {field.input}
                    </Form.Item>
                ))}
            </Form>
        </Modal>
    );
}