import { Modal } from "antd";
import '../scss/ApproveResetModal.scss'

export default function ApproveResetModal({ open, onOk, onCancel, resetEmail, tempPassword }) {
    return (
        <Modal
            open={open}
            onOk={onOk}
            onCancel={onCancel}
            title="Password Reset Approved"
            centered
            className="request-approved"
        >

            <div>
                <p><strong>Email:</strong> {resetEmail}</p>
                <p><strong>Temporary Password:</strong> <code>{tempPassword}</code></p>
            </div>

        </Modal>
    )
}