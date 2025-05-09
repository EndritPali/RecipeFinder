import { useEffect } from 'react';

export function useRecipeForm(form, isEdit, item, open) {
    useEffect(() => {
        if (isEdit && item) {
            form.setFieldsValue({
                recipetitle: item.recipetitle,
                category: item.category,
                rating: item.rating,
                preparation: item.preparation,
                ingredients: item.ingredients,
                shortdescription: item.shortdescription,
                servings: item.servings,
                preptime: item.preptime,
                cooktime: item.cooktime,
                image: item.image,
            });
        } else {
            form.resetFields();
        }
    }, [isEdit, item, form, open]);
}
