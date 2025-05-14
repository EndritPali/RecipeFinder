import '../Scss/Header.scss';
import Magnify from '../assets/MagnifyingGlass.svg';
import User from '../assets/User.svg';
import Heart from '../assets/Heart.svg';
import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { Dropdown, AutoComplete, Skeleton, Menu, Empty } from 'antd';
import AccountModal from '../Templates/AccountModal';
import { useFetchRecipes } from '../hooks/useFetchRecipes';
import { useSavedRecipes } from '../hooks/useSavedRecipes';
import RecipeDetailsModal from '../Templates/RecipeDetailsModal';

export default function Header() {
  const [isAccountModalOpen, setIsAccountModalOpen] = useState(false);
  const [isRecipeModalOpen, setIsRecipeModalOpen] = useState(false);
  const [modalMode, setModalMode] = useState('login');
  const [user, setUser] = useState(null);
  const [showSearch, setShowSearch] = useState(false);
  const [filteredOptions, setFilteredOptions] = useState([]);
  const [selectedRecipe, setSelectedRecipe] = useState(null);
  const { recipes, loading } = useFetchRecipes();
  const { savedRecipes, loading: savedLoading } = useSavedRecipes();

  useEffect(() => {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      setUser(JSON.parse(storedUser));
    }
  }, []);

  const handleSearch = (value) => {
    const filtered = recipes
      .filter(r => r.recipetitle.toLowerCase().includes(value.toLowerCase()))
      .map(r => ({
        value: r.recipetitle,
        id: r.key,
        label: (
          <div className="search-suggestion">
            <img src={r.image} alt={r.recipetitle} width={30} />
            <span>{r.recipetitle}</span>
          </div>
        ),
        recipe: r
      }));
    setFilteredOptions(filtered);
  };

  const handleSelect = (value, option) => {
    const recipe = option.recipe;
    if (recipe) {
      setSelectedRecipe(recipe);
      setIsRecipeModalOpen(true);
    }
    setShowSearch(false);
  };

  const handleSavedRecipeClick = (recipe) => {
    setSelectedRecipe(recipe);
    setIsRecipeModalOpen(true);
  };

  const handleCloseRecipeModal = () => {
    setIsRecipeModalOpen(false);
    setSelectedRecipe(null);
  };

  const openAccountModal = (mode) => {
    setModalMode(mode);
    setIsAccountModalOpen(true);
  };

  const menuItems = user ? [
    { key: 'username', label: user.username },
    { key: 'divider', type: 'divider' },
    { key: 'admin', label: <Link to="/admin">Dashboard</Link> },
  ] : [
    { key: 'login', label: 'Login', onClick: () => openAccountModal('login') },
    { key: 'divider', type: 'divider' },
    { key: 'register', label: 'Register', onClick: () => openAccountModal('register') },
  ];

  const savedRecipesMenu = (
    <Menu className="saved-recipes-dropdown">
      <Menu.Item key="header" disabled className="saved-recipes-header">
        Saved Recipes
      </Menu.Item>
      <Menu.Divider />
      {savedLoading ? (
        <Menu.Item key="loading" disabled>
          <Skeleton active paragraph={{ rows: 1 }} />
        </Menu.Item>
      ) : savedRecipes.length === 0 ? (
        <Menu.Item key="empty" disabled>
          <Empty description="No saved recipes" image={Empty.PRESENTED_IMAGE_SIMPLE} />
        </Menu.Item>
      ) : (
        savedRecipes.map(recipe => (
          <Menu.Item key={recipe.key} onClick={() => handleSavedRecipeClick(recipe)}>
            <div className="saved-recipe-item">
              {recipe.image && (
                <img src={recipe.image} alt={recipe.recipetitle} width={30} height={30} />
              )}
              <span>{recipe.recipetitle}</span>
            </div>
          </Menu.Item>
        ))
      )}
    </Menu>
  );

  return (
    <>
      <div className="header">
        <div className="header__logo">
          <div className="header__logo-box">
            <Dropdown menu={{ items: menuItems }} placement='bottomRight'>
              <i className="fas fa-bars"></i>
            </Dropdown>
          </div>
          <h1><span>Recipe</span> finder</h1>
        </div>

        <div className="header__search--mobile">
          <i className="fas fa-search"></i>
          <AutoComplete
            style={{ width: 300 }}
            options={filteredOptions}
            onSearch={handleSearch}
            onSelect={handleSelect}
            placeholder="Search for recipes"
            autoFocus
            filterOption={false}
          />
        </div>

        <div className="header__widgets">
          <button onClick={() => setShowSearch(prev => !prev)}>
            <img src={Magnify} alt="magnify" />
          </button>

          {showSearch && (
            <AutoComplete
              style={{ width: 300 }}
              options={filteredOptions}
              onSearch={handleSearch}
              onSelect={handleSelect}
              placeholder="Search for recipes"
              autoFocus
              filterOption={false}
            />
          )}

          {loading && <Skeleton active paragraph={{ rows: 1 }} />}

          {user && (
            <Dropdown overlay={savedRecipesMenu} placement='bottomRight' trigger={['click']}>
              <button><img src={Heart} alt="heart" /></button>
            </Dropdown>
          )}

          <Dropdown menu={{ items: menuItems }} placement='bottomRight'>
            <button><img src={User} alt="user" /></button>
          </Dropdown>
        </div>
      </div>

      {selectedRecipe && (
        <RecipeDetailsModal
          open={isRecipeModalOpen}
          onOk={handleCloseRecipeModal}
          onCancel={handleCloseRecipeModal}
          recipe={selectedRecipe}
        />
      )}

      <AccountModal
        open={isAccountModalOpen}
        onOk={() => setIsAccountModalOpen(false)}
        onCancel={() => setIsAccountModalOpen(false)}
        mode={modalMode}
      />
    </>
  );
}