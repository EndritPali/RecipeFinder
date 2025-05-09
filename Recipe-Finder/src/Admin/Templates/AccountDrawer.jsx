import { Drawer, Card, Space, Avatar } from "antd";
import { useEffect, useState } from "react";
import {
    BookOutlined,
    CalendarOutlined,
    UserOutlined,
    SafetyCertificateOutlined,
    IdcardOutlined,
    MailOutlined,
    KeyOutlined,
    EditOutlined,
    EyeOutlined
} from '@ant-design/icons';
import '../scss/AccountDrawer.scss';
import DrawerInput from "./DrawerInputs";
import api from '../../Services/api';
import auth from '../../Services/auth';

export default function AccountDrawer({ open, onClose }) {
    const [isEditing, setIsEditing] = useState(false);
    const [userBasicInfo, setUserBasicInfo] = useState({});
    const [userFullInfo, setUserFullInfo] = useState(null);
    const [myRecipes, setMyRecipes] = useState([]);
    const [loading, setLoading] = useState(false);

    const handleEditClick = () => setIsEditing(!isEditing);
    const cancelEditing = () => setIsEditing(false);

    useEffect(() => {
        if (open) {
            setLoading(true);

            const storedUser = localStorage.getItem('user');
            if (storedUser) {
                setUserBasicInfo(JSON.parse(storedUser));
            }

            const fetchUserData = async () => {
                try {
                    const userData = await auth.getCurrentUser();
                    setUserFullInfo(userData);
                } catch (error) {
                    console.error('Error fetching user data:', error);
                }
            };

            const fetchMyRecipes = async () => {
                try {
                    const response = await api.get('/my-recipes');
                    const myRecipes = response.data.data;
                    setMyRecipes(myRecipes);
                } catch (error) {
                    console.error('Error fetching user recipes:', error);
                } finally {
                    setLoading(false);
                }
            };

            Promise.all([fetchUserData(), fetchMyRecipes()]);
        }
    }, [open]);

    const user = {
        ...userBasicInfo,
        role: userFullInfo?.role || 'Loading...'
    };

    return (
        <Drawer title="Account Settings" open={open} onClose={onClose} loading={loading}>
            <div className="user">
                <div className="user-profile-pic">
                    <Avatar icon={<UserOutlined />} />
                </div>
                <div className="user-profile-name">
                    <h2>{user.username}</h2>
                </div>
            </div>

            <Card title={<Space><UserOutlined /> User Profile</Space>}>
                <DrawerInput icon={<IdcardOutlined />} header="Role:" information={user.role} />
                <DrawerInput icon={<CalendarOutlined />} header="Date Created:" information={user.date} />
                <DrawerInput icon={<BookOutlined />} header="Recipes Created:" information={myRecipes.map(recipe => recipe.title).join(', ')} />
            </Card>

            <Card title={<Space><SafetyCertificateOutlined /> Security</Space>}>
                <DrawerInput
                    icon={<MailOutlined />}
                    header="Email:"
                    information={user.email}
                />
                <DrawerInput
                    icon={<KeyOutlined />}
                    header="Password:"
                    information={user.password}
                    isEditing={isEditing}
                >
                    {isEditing ? (
                        <div className="btns">
                            <button type="button" onClick={cancelEditing}>Cancel</button>
                            <button type="button">Save</button>
                        </div>
                    ) : (
                        <div className="btns">
                            <button type="button"><EyeOutlined /></button>
                            <button type="button" onClick={handleEditClick}><EditOutlined /></button>
                        </div>
                    )}
                </DrawerInput>
            </Card>
        </Drawer>
    );
}