import { Form, Input, Card, Button } from "antd"
import '@/Scss/ResetPassword.scss'
import { useState } from "react"
import { usePasswordResetRequest } from "@/features/auth/hooks/usePasswordRequest"
import { useNavigate } from "react-router-dom"

type RequestStatus = {
    requestId: string;
    timestamp: string;
} | null;

export default function ResetPassword() {
    const [isSubmitting] = useState(false);
    const passwordResetRequest = usePasswordResetRequest();
    const [isSubmitted, setIsSubmitted] = useState(false);
    const [requestStatus, setRequestStatus] = useState<RequestStatus>(null);
    const navigate = useNavigate();

    const handleSubmit = async (values: { username: string; email: string; password: string }) => {
        try {
            const data = await passwordResetRequest.mutateAsync({
                username: values.username,
                email: values.email,
                last_password: values.password
            });
            setIsSubmitted(true);
            if (data && data.request_id) {
                setRequestStatus({
                    requestId: data.request_id,
                    timestamp: new Date().toLocaleString()
                });
            }
        } catch
        (error) {
            console.error('Reset request error:', error);
        }
    };

    const handleBack = () => {
        navigate('/');
    };

    return (
        <>
            <div className="container">
                <Card title='Password Reset' className="reset-form-card">
                    {!isSubmitted ? (
                        <Form
                            layout="vertical"
                            className="reset-form"
                            onFinish={handleSubmit}
                        >
                            <Form.Item
                                className="reset-form-input"
                                label='Username'
                                name="username"
                                rules={[{ required: true, message: 'Please input username' }]}
                            >
                                <Input placeholder="Enter your Username" />
                            </Form.Item>
                            <Form.Item
                                className="reset-form-input"
                                label='Email'
                                name="email"
                                rules={[
                                    { required: true, message: 'Please input email!' },
                                    { type: 'email', message: 'Please enter a valid email!' }
                                ]}
                            >
                                <Input placeholder="Enter your email" />
                            </Form.Item>
                            <Form.Item
                                className="reset-form-input"
                                label='Last password you remember'
                                name="password"
                                rules={[{ required: true, message: 'Please input password!' }]}
                            >
                                <Input.Password placeholder="Enter your password" />
                            </Form.Item>
                            <div className="reset-form-buttons">
                                <Button onClick={handleBack}>Back</Button>
                                <Button type="primary" htmlType="submit" loading={isSubmitting}>
                                    Submit
                                </Button>
                            </div>
                        </Form>
                    ) : (
                        <div className="reset-success">
                            <h3>Request Submitted</h3>
                            <p>Your password reset request has been submitted to administrators.
                                If approved, you will receive an email with further instructions.</p>
                            {requestStatus && (
                                <div className="request-info">
                                    <p><strong>Request ID:</strong> {requestStatus.requestId}</p>
                                    <p><strong>Submitted:</strong> {requestStatus.timestamp}</p>
                                </div>
                            )}
                            <Button onClick={handleBack} type="primary">Back to Login</Button>
                        </div>
                    )}
                </Card>
            </div>
        </>
    )
}