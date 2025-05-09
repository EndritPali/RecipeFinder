import { useState } from 'react';
import { Button, Input, Space, Radio, } from 'antd';
import { PlusCircleOutlined, UnorderedListOutlined, AppstoreOutlined, SearchOutlined, UserAddOutlined } from '@ant-design/icons';
import '../scss/DashboardFilter.scss'
import RecipeModal from '../Templates/RecipeModal';
import CreateUserModal from '../Templates/UserModal';

export default function DashboardFilter({ view, setView, searchTerm, setSearchTerm }) {
    const [isModalOpen, setIsModalOpen] = useState(false);

    const showModal = () => {
        setIsModalOpen(true);
    };

    const handleOk = () => {
        setIsModalOpen(false);
    };

    const handleCancel = () => {
        setIsModalOpen(false);
    };

    const isUsersPage = location.pathname === '/admin/users';

    return (
        <>
            <div className="dashboard-filter">
                <div className="recipe-action-button">
                    <Button onClick={showModal} icon={isUsersPage ? <UserAddOutlined /> : <PlusCircleOutlined />}>
                        <p>{isUsersPage ? 'Create User' : 'Add new recipe'}</p>
                    </Button>
                </div>

                <div className="filter-section">
                    <Space className='search-bar'>
                        <SearchOutlined />
                        <Input
                            placeholder={isUsersPage ? 'Search by username' : 'Search by title'}
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                        ></Input>
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
                <CreateUserModal open={isModalOpen} onOk={handleOk} onCancel={handleCancel} />
            ) : (
                <RecipeModal open={isModalOpen} onOk={handleOk} onCancel={handleCancel} mode="create" />
            )}
        </>
    )
}