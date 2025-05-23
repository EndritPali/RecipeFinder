import { Modal, Form, Input, InputNumber, Select } from 'antd';
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

            await api[isEdit ? 'put' : 'post'](
                isEdit ? `v1/recipes/${item.key}` : 'v1/recipes', payload
            );
            onOk();
        } catch (error) {
            console.error('Error saving recipe:', error);
        }
    };

    return (
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

                    {['preparation', 'ingredients', 'shortdescription'].map(name => (
                        <Form.Item key={name} name={name}>
                            <TextArea placeholder={`Enter ${name.replace(/^./, c => c.toUpperCase())}`} />
                        </Form.Item>
                    ))}

                    <div className="input-spaced-multiple">
                        {['servings', 'preptime', 'cooktime'].map(name => (
                            <Form.Item key={name} name={name}>
                                <Input placeholder={`Enter ${name.includes('time') ? name.replace('time', ' time') : name}`} />
                            </Form.Item>
                        ))}
                    </div>
                </div>
            </Form>
        </Modal>
    );
}