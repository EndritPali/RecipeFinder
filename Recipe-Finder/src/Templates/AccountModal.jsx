import { Modal, Form, Input, message } from 'antd';
import { useState } from 'react';
import api from '../Services/api';
import '../Scss/AccountModal.scss';

export default function AccountModal({ open, onOk, onCancel, mode = 'login' }) {
    const [form] = Form.useForm();
    const [loading, setLoading] = useState(false);
    const isRegistering = mode === 'register';

    const handleFinish = async (values) => {
        setLoading(true);
        try {
            let response;

            if (isRegistering) {
                response = await api.post('api/v1/auth/register', {
                    username: values.username,
                    email: values.email,
                    password: values.password,
                    role: 'User',
                });

                if (response.data.status === 'success') {
                    message.success('Registration Successful! Please login.');
                    form.resetFields();
                    onOk();
                }
            } else {
                response = await api.post('/auth/login', {
                    email: values.email,
                    password: values.password,
                });

                if (response.data.status === 'success') {
                    localStorage.setItem('token', response.data.token);
                    localStorage.setItem('user', JSON.stringify({
                        username: response.data.user.username,
                        email: response.data.user.email,
                        date: response.data.user.created_at,
                    }));

                    message.success('Login Successful!');
                    form.resetFields();
                    onOk();
                    window.location.reload();
                }
            }

        } catch (error) {
            console.error('Error:', error.response?.data || error);
            message.error(isRegistering ? 'Registration failed. Please try again.' : 'Login failed. Please check your credentials.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <Modal
            open={open}
            onOk={() => form.submit()}
            onCancel={onCancel}
            okText={isRegistering ? 'Register' : 'Sign In'}
            title={isRegistering ? 'Register new account' : 'Sign In'}
            className='account-modal-login'
            confirmLoading={loading}
        >
            <div className="auth-header">
                <h1>Recipe <span>finder</span></h1>
            </div>
            <Form className='auth-form' form={form} onFinish={handleFinish} layout='vertical'>

                {isRegistering && (
                    <Form.Item
                        className='user-form-item'
                        label='Username'
                        name="username"
                        rules={[{ required: true, message: 'Please input username' }]}
                    >
                        <Input placeholder='Enter Username' />
                    </Form.Item>
                )}

                <Form.Item
                    className='user-form-item'
                    label='Email'
                    name="email"
                    rules={[
                        { required: true, message: 'Please input email!' },
                        { type: 'email', message: 'Please enter a valid email!' },
                    ]}
                >
                    <Input placeholder='Enter email' />
                </Form.Item>

                <Form.Item
                    className='user-form-item'
                    label='Password'
                    name="password"
                    rules={[{ required: true, message: 'Please input password!' }]}
                >
                    <Input.Password placeholder='Enter password' />
                </Form.Item>

                {!isRegistering && (
                    <Form.Item>
                        <a href="/reset-password">Forgot password?</a>
                    </Form.Item>
                )}
            </Form>
        </Modal>
    );
}
