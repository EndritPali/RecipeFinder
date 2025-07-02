import { Modal, Form, Input, message } from 'antd';
import { useEffect, useRef } from 'react';
import { useAuth } from '@/context/AuthContext';
import '@/Scss/AccountModal.scss';
import { AccountModalProps, LoginFormValues } from '@/types/auth';

export default function AccountModal({ open, onOk, onCancel, mode = 'login' }: AccountModalProps) {
    const [form] = Form.useForm();
    const isRegistering = mode === 'register';
    const hasShownSuccessRef = useRef(false);

    const auth = useAuth();
    const loginMutation = auth?.loginMutation;
    const registerMutation = auth?.registerMutation;

    const handleFinish = async (values: LoginFormValues) => {
        try {
            if (isRegistering) {
                await registerMutation.mutateAsync(values);
                message.success('Registration Successful! Please login.');
            } else {
                await loginMutation.mutateAsync(values);
                message.success('Login Successful!');
            }
            form.resetFields();
            onOk();
        } catch (error: any) {
            const defaultMessage = isRegistering ? 'Registration failed.' : 'Login failed.';
            message.error(error?.response?.data?.message || defaultMessage);
        }
    };

    useEffect(() => {
        hasShownSuccessRef.current = false;
    }, [open, mode]);


    return (
        <Modal
            open={open}
            onOk={() => form.submit()}
            onCancel={onCancel}
            okText={isRegistering ? 'Register' : 'Sign In'}
            title={isRegistering ? 'Register new account' : 'Sign In'}
            className='account-modal-login'
            confirmLoading={isRegistering ? registerMutation.isLoading : loginMutation.isLoading}
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
