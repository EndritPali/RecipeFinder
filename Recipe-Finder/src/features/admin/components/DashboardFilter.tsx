import { useState } from 'react';
import { Button, Input, Space, Radio } from 'antd';
import { PlusCircleOutlined, UnorderedListOutlined, AppstoreOutlined, SearchOutlined, UserAddOutlined } from '@ant-design/icons';
import '../scss/DashboardFilter.scss';
import RecipeModal from '../templates/RecipeModal';
import CreateUserModal from '../templates/UserModal';
import { useLocation } from 'react-router-dom';

interface DashboardFilterProps {
    view: string;
    setView: (view: string) => void;
    searchTerm: string;
    setSearchTerm: (term: string) => void;
    onDataChanged: () => void;
}

export default function DashboardFilter({ view, setView, searchTerm, setSearchTerm, onDataChanged }: DashboardFilterProps) {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const location = useLocation();
    const isUsersPage = location.pathname === '/admin/users';

    const showModal = () => {
        setIsModalOpen(true);
    };

    const handleOk = () => {
        setIsModalOpen(false);
        
        if (onDataChanged) {
            onDataChanged();
        }
    };

    const handleCancel = () => {
        setIsModalOpen(false);
    };

    return (
        <>
            <div className="dashboard-filter">
                <div className="recipe-action-button">
                    <Button onClick={showModal} icon={isUsersPage ? <UserAddOutlined /> : <PlusCircleOutlined />}>
                        <p>{isUsersPage ? 'Create User' : 'Add new recipe'}</p>
                    </Button>
                </div>

                <div className="filter-section">
                    <Space className="search-bar">
                        <SearchOutlined />
                        <Input
                            placeholder={isUsersPage ? 'Search by username' : 'Search by title'}
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                        />
                    </Space>

                    <div className="filter-buttons">
                        <Radio.Group value={view} onChange={(e) => setView(e.target.value)}>
                            <Radio.Button value="list">
                                <UnorderedListOutlined />
                            </Radio.Button>
                            <Radio.Button value="grid">
                                <AppstoreOutlined />
                            </Radio.Button>
                        </Radio.Group>
                    </div>
                </div>
            </div>

            {isUsersPage ? (
                <CreateUserModal
                    open={isModalOpen}
                    onOk={handleOk}
                    onCancel={handleCancel}
                    mode="create"
                />
            ) : (
                <RecipeModal
                    open={isModalOpen}
                    onOk={handleOk}
                    onCancel={handleCancel}
                    mode="create"
                />
            )}
        </>
    );
}