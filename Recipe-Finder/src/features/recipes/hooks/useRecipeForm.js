import { useEffect } from 'react';

export function useRecipeForm(form, isEdit, item, open) {
    useEffect(() => {
        if (!open) return;

        if (isEdit && item) {
            form.setFieldsValue({
                recipetitle: item.recipetitle || item.title,
                category: item.category,
                rating: item.rating,
                preparation: item.preparation || item.instructions,
                ingredients: Array.isArray(item.ingredients) 
                    ? item.ingredients.join(', ') 
                    : item.ingredients,
                shortdescription: item.shortdescription || item.short_description,
                servings: item.servings,
                preptime: item.preptime || item.preparation_time,
                cooktime: item.cooktime || item.cooking_time,
                image: item.image || item.image_url,
            });
        } else {
            form.resetFields();
        }
    }, [isEdit, item, form, open]);
}