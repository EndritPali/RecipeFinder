import { Form, Input, Card, Button } from "antd"
import '../Scss/ResetPassword.scss'

export default function ResetPassword() {
    return (
        <>
            <div className="container">
                <Card title='Password Reset' className="reset-form-card">
                    <Form layout="vertical" className="reset-form">
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
                            label='Password'
                            name="password"
                            rules={[{ required: true, message: 'Please input password!' }]}
                        >
                            <Input placeholder="Enter your password" />
                        </Form.Item>
                        <div className="reset-form-buttons">
                            <Button href="/">Back</Button>
                            <Button>Submit</Button>
                        </div>
                    </Form>
                </Card>
            </div>
        </>
    )
}