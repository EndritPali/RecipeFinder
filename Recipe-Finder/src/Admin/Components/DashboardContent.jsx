import '../scss/DashboardContent.scss';
import DashboardFilter from './DashboardFilter';
import RecipeModal from '../Templates/RecipeModal';
import RecipeGrid from '../Templates/GridSort';
import UserModal from '../Templates/UserModal';
import { Table } from 'antd';
import { useState } from 'react';
import { useDashboardLogic } from '../../hooks/useDashboardLogic';

export default function DashboardContent() {
    const [view, setView] = useState('list');
    const [searchTerm, setSearchTerm] = useState('');
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedItem, setSelectedItem] = useState(null);

    const {
        isUserDashboard,
        filteredData,
        columns,
        loading,
        fetchUsers,
        fetchRecipes,
        handleShowModal,
        handleDelete
    } = useDashboardLogic(searchTerm, setIsModalOpen, setSelectedItem);

    const handleCloseModal = () => {
        if (isUserDashboard) {
            fetchUsers();
        } else {
            fetchRecipes();
        }
        setIsModalOpen(false);
        setSelectedItem(null);
    };

    const handleDataChanged = () => {
        if (isUserDashboard) {
            fetchUsers();
        } else {
            fetchRecipes();
        }
    };

    return (
        <div className="content-container">
            <DashboardFilter
                view={view}
                setView={setView}
                searchTerm={searchTerm}
                setSearchTerm={setSearchTerm}
                onDataChanged={handleDataChanged}
            />

            {view === 'list' ? (
                <Table
                    scroll={{ x: "max-content" }}
                    columns={columns}
                    dataSource={filteredData}
                    loading={loading}
                    pagination={isUserDashboard ? { showTotal: (total) => `${total} user(s) in total`, } : {
                        showTotal: (total) => `${total} recipe(s) in total`,
                    }}
                    rowKey="key"
                />
            ) : (
                <RecipeGrid
                    data={filteredData}
                    onEdit={handleShowModal}
                    onDelete={handleDelete}
                />
            )}

            {isUserDashboard ? (
                <UserModal
                    open={isModalOpen}
                    onOk={handleCloseModal}
                    onCancel={handleCloseModal}
                    item={selectedItem}
                    mode="edit"
                />
            ) : (
                <RecipeModal
                    open={isModalOpen}
                    onOk={handleCloseModal}
                    onCancel={handleCloseModal}
                    item={selectedItem}
                    mode="edit"
                />
            )}
        </div>
    );
}