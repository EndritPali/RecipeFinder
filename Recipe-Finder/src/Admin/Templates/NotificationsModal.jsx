import { Modal, List, Avatar, Button } from "antd"
import { UserOutlined } from '@ant-design/icons';
import '../scss/NotificationsModal.scss'

export default function NotificationsModal({ open, onOk, onCancel, }) {
    return (
        <>
            <Modal className="notifications-modal"
                open={open}
                onOk={onOk}
                onCancel={onCancel}
                title='Notifications Panel (Password Reset)'
            >
                <List itemLayout="horizontal" >
                    <List.Item>
                        <List.Item.Meta
                            avatar={<Avatar icon={<UserOutlined />} />}
                            title='Request from: MarouaneFellaini@mufc.uk.gov'
                            description='Last password i can remember is: marou something'

                        >
                        </List.Item.Meta>
                        <div className="list-buttons">
                            <Button>Accept</Button>
                            <Button>Deny</Button>
                        </div>
                    </List.Item>
                    <List.Item>
                        <List.Item.Meta
                            avatar={<Avatar icon={<UserOutlined />} />}
                            title='Request from: UserE@email.com'
                            description='Last password i can remember is: niceuser123'

                        >
                        </List.Item.Meta>
                        <div className="list-buttons">
                            <Button>Accept</Button>
                            <Button>Deny</Button>
                        </div>
                    </List.Item>
                </List>

            </Modal>
        </>
    )
}