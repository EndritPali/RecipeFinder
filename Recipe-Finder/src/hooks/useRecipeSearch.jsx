import { useState } from 'react';

export function useRecipeSearch(recipes, setSelectedRecipe, setIsRecipeModalOpen, setShowSearch) {
  const [filteredOptions, setFilteredOptions] = useState([]);

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

  return { filteredOptions, handleSearch, handleSelect };
}