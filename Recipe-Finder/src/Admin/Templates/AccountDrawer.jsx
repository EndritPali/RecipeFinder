import { Drawer, Card, Space, Avatar, message } from "antd";
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
} from '@ant-design/icons';
import '../scss/AccountDrawer.scss';
import DrawerInput from "./DrawerInputs";
import api from '../../Services/api';
import auth from '../../Services/auth';

export default function AccountDrawer({ open, onClose }) {
    const [isEditing, setIsEditing] = useState(false);
    const [editingField, setEditingField] = useState(null);
    const [userBasicInfo, setUserBasicInfo] = useState({});
    const [userFullInfo, setUserFullInfo] = useState(null);
    const [myRecipes, setMyRecipes] = useState([]);
    const [loading, setLoading] = useState(false);
    const [editedEmail, setEditedEmail] = useState('');
    const [editedPassword, setEditedPassword] = useState('');

    const handleEditClick = (field) => {
        setIsEditing(true);
        setEditingField(field);

        // Initialize edited values
        if (field === 'email') setEditedEmail(user.email);
        if (field === 'password') setEditedPassword('');
    };

    const cancelEditing = () => {
        setIsEditing(false);
        setEditingField(null);
        setEditedEmail('');
        setEditedPassword('');
    };

    const handleSave = async () => {
        try {
            setLoading(true);

            // Ensure we have a valid user ID
            const userId = userBasicInfo.id || userFullInfo?.id;
            if (!userId) {
                throw new Error('User ID not found');
            }

            if (editingField === 'email') {
                await handleUpdateUser(userId, { email: editedEmail });
            } else if (editingField === 'password') {
                await handleUpdateUser(userId, { password: editedPassword });
            }

            cancelEditing();
        } catch (error) {
            message.error(error.message || `Failed to update ${editingField}`);
        } finally {
            setLoading(false);
        }
    };

    const handleUpdateUser = async (userId, payload) => {
        try {
            // Update user via API using the put method with user ID
            const response = await api.put(`/user/${userId}`, payload);

            // Update local state
            const updatedUser = {
                ...userBasicInfo,
                ...payload
            };
            setUserBasicInfo(updatedUser);

            // Update localStorage
            localStorage.setItem('user', JSON.stringify(updatedUser));

            message.success('User information updated successfully');
            return response.data;
        } catch (error) {
            console.error(`Error updating user:`, error);

            // More detailed error handling
            if (error.response) {
                // The request was made and the server responded with a status code
                // that falls out of the range of 2xx
                message.error(error.response.data.message || 'Failed to update user');
            } else if (error.request) {
                // The request was made but no response was received
                message.error('No response received from server');
            } else {
                // Something happened in setting up the request that triggered an Error
                message.error('Error setting up the request');
            }

            throw error;
        }
    };

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
        ...(userFullInfo || {}),
        role: userFullInfo?.role || userBasicInfo.role || 'Loading...'
    };

    return (
        <Drawer
            title="Account Settings"
            open={open}
            onClose={onClose}
            loading={loading ? 1 : 0}
        >
            <div className="user">
                <div className="user-profile-pic">
                    <Avatar icon={<UserOutlined />} />
                </div>
                <div className="user-profile-name">
                    <h2>{user.username}</h2>
                </div>
            </div>

            <Card title={<Space><UserOutlined /> User Profile</Space>}>
                <DrawerInput
                    icon={<IdcardOutlined />}
                    header="Role:"
                    information={user.role}
                />
                <DrawerInput
                    icon={<CalendarOutlined />}
                    header="Date Created:"
                    information={user.date}
                />
                <DrawerInput
                    icon={<BookOutlined />}
                    header="Recipes Created:"
                    information={myRecipes.map(recipe => recipe.title).join(', ')}
                />
            </Card>

            <Card title={<Space><SafetyCertificateOutlined /> Security</Space>}>
                <DrawerInput
                    icon={<MailOutlined />}
                    header="Email:"
                    information={user.email}
                    isEditing={isEditing && editingField === 'email'}
                    value={editedEmail}
                    onValueChange={setEditedEmail}
                >
                    {isEditing && editingField === 'email' ? (
                        <div className="btns">
                            <button type="button" onClick={cancelEditing}>Cancel</button>
                            <button type="button" onClick={handleSave}>Save</button>
                        </div>
                    ) : (
                        <div className="btns">
                            <button type="button" onClick={() => handleEditClick('email')}>
                                <EditOutlined />
                            </button>
                        </div>
                    )}
                </DrawerInput>
                <DrawerInput
                    icon={<KeyOutlined />}
                    header="Password:"
                    information="********"
                    isEditing={isEditing && editingField === 'password'}
                    value={editedPassword}
                    onValueChange={setEditedPassword}
                >
                    {isEditing && editingField === 'password' ? (
                        <div className="btns">
                            <button type="button" onClick={cancelEditing}>Cancel</button>
                            <button type="button" onClick={handleSave}>Save</button>
                        </div>
                    ) : (
                        <div className="btns">
                            <button type="button" onClick={() => handleEditClick('password')}>
                                <EditOutlined />
                            </button>
                        </div>
                    )}
                </DrawerInput>
            </Card>
        </Drawer>
    );
}