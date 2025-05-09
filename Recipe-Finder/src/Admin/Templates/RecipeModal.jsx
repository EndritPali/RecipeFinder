import { Modal, Form, Input, Upload, Button, InputNumber, Select } from 'antd';
import '../scss/RecipeModal.scss';
import { useRecipeForm } from '../../hooks/useRecipeForm';

import api from '../../Services/api';
import FormImageUpload from './ImgUpload';

export default function RecipeModal({ open, onOk, onCancel, mode = 'create', item }) {
    const { TextArea } = Input;
    const isEdit = mode === 'edit';
    const [form] = Form.useForm();

    useRecipeForm(form, isEdit, item, open);

    const handleFinish = async (values) => {
        try {
            const payload = {
                title: values.recipetitle,
                short_description: values.shortdescription,
                rating: Number(values.rating),
                category: values.category,
                image_url: values.image,
                instructions: values.preparation,
                ingredients: values.ingredients,
                preparation_time: Number(values.preptime),
                cooking_time: Number(values.cooktime),
                servings: Number(values.servings),
            };

            if (isEdit) {
                await api.put(`/recipes/${item.key}`, payload);
            } else {
                await api.post('/recipes', payload);
            }

            onOk();
        } catch (error) {
            if (error.response && error.response.status === 422) {
                console.error("Validation errors:", error.response.data.errors);
            } else {
                console.error('Error saving recipe:', error);
            }
        }
        
    };

    return (
        <>
            <Modal
                title={isEdit ? 'Edit Recipe' : 'Create New Recipe'}
                open={open}
                onOk={() => form.submit()}
                onCancel={onCancel}
                okText={isEdit ? 'Save Changes' : 'Create Recipe'}
            >
                <Form form={form} onFinish={handleFinish}>
                    <FormImageUpload form={form} />
                    <div className="form-inputs">
                        <Form.Item name="recipetitle" rules={[{ required: true, message: 'Please enter a recipe title' }]}>
                            <Input placeholder='Enter recipe title' />
                        </Form.Item>

                        <div className="input-spaced">
                            <Form.Item name="category">
                                <Select placeholder='Select Category'>
                                    <Select.Option value='With Features'>With Features</Select.Option>
                                    <Select.Option value='With benefits'>With benefits</Select.Option>
                                </Select>
                            </Form.Item>
                            <Form.Item name="rating">
                                <InputNumber placeholder='Enter Rating' min={0} max={5} />
                            </Form.Item>
                        </div>

                        <Form.Item name="preparation">
                            <TextArea placeholder='Enter preparation steps' />
                        </Form.Item>
                        <Form.Item name="ingredients">
                            <TextArea placeholder='Enter ingredients list' />
                        </Form.Item>
                        <Form.Item name="shortdescription">
                            <TextArea placeholder='Enter short description' />
                        </Form.Item>

                        <div className="input-spaced-multiple">
                            <Form.Item name="servings">
                                <Input placeholder='Enter amount of servings' />
                            </Form.Item>
                            <Form.Item name="preptime">
                                <Input placeholder='Enter preparation time' />
                            </Form.Item>
                            <Form.Item name="cooktime">
                                <Input placeholder='Enter cooking time' />
                            </Form.Item>
                        </div>
                    </div>
                </Form>
            </Modal>
        </>
    )
}