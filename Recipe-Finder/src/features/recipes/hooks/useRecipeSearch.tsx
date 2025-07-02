import { useMemo, useState } from "react";
import { useFetchRecipes } from "./useFetchRecipes.js";

export function useRecipeSearch() {
  const [searchTerm, setSearchTerm] = useState("");

  const { data } = useFetchRecipes({
    paginate: false,
    enabled: !!searchTerm,
  });

  const recipes = data?.recipes;

  const filteredOptions = useMemo(() => {
    if (!recipes || !searchTerm) return [];

    return recipes
      .filter((r: any) => r.recipetitle.toLowerCase().includes(searchTerm.toLowerCase()))
      .map((r: any) => ({
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
  }, [recipes, searchTerm]);

  return {
    options: filteredOptions,
    onSearch: setSearchTerm,
  };
}