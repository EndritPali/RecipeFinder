import { Modal, Form, Input, InputNumber, Select, FormInstance } from 'antd';
import '../scss/RecipeModal.scss';
import { useRecipeForm } from '../../recipes/hooks/useRecipeForm';
import { useRecipeMutations } from '@/features/admin/hooks/useRecipeMutations';
import FormImageUpload from './ImgUpload';
import { RecipeModalProps } from '@/types/admin';
import type { Rule } from 'antd/es/form';

export default function RecipeModal({ open, onOk, onCancel, mode = 'create', item }: RecipeModalProps) {
    const { TextArea } = Input;
    const isEdit = mode === 'edit';
    const [form] = Form.useForm();
    useRecipeForm(form, isEdit, item, open);
    const { createRecipe, updateRecipe } = useRecipeMutations();

    const handleFinish = async (values: any) => {
        if (isEdit) {
            await updateRecipe.mutateAsync({ id: item.key, values });
        } else {
            await createRecipe.mutateAsync(values);
        }
        onOk();
    };

    return (
        <Modal
            title={isEdit ? 'Edit Recipe' : 'Create New Recipe'}
            open={open}
            onOk={() => form.submit()}
            onCancel={onCancel}
            okText={isEdit ? 'Save Changes' : 'Create Recipe'}
            confirmLoading={isEdit ? updateRecipe.status === 'pending' : createRecipe.status === 'pending'}
        >
            <Form form={form} onFinish={handleFinish}>
                <FormImageUpload form={form} />
                <div className="form-inputs">
                    <Form.Item name="recipetitle" rules={[{ required: true, message: 'Please enter a recipe title' }] as Rule[]}>
                        <Input placeholder='Enter recipe title' />
                    </Form.Item>

                    <div className="input-spaced">
                        <Form.Item name="category">
                            <Select placeholder='Select Category'>
                                <Select.Option value='With Features'>With Features</Select.Option>
                                <Select.Option value='With Benefits'>With Benefits</Select.Option>
                            </Select>
                        </Form.Item>
                        <Form.Item name="rating">
                            <InputNumber placeholder='Enter Rating' min={0} max={5} />
                        </Form.Item>
                    </div>

                    {['preparation', 'ingredients', 'shortdescription'].map(name => (
                        <Form.Item key={name} name={name} rules={[] as Rule[]}>
                            <TextArea placeholder={`Enter ${name.replace(/^./, c => c.toUpperCase())}`} />
                        </Form.Item>
                    ))}

                    <div className="input-spaced-multiple">
                        {['servings', 'preptime', 'cooktime'].map(name => (
                            <Form.Item key={name} name={name} rules={[] as Rule[]}>
                                <Input placeholder={`Enter ${name.includes('time') ? name.replace('time', ' time') : name}`} />
                            </Form.Item>
                        ))}
                    </div>
                </div>
                <Form.Item style={{ display: 'none' }}>
                    <button type="submit" tabIndex={-1}></button>
                </Form.Item>
            </Form>
        </Modal>
    );
}