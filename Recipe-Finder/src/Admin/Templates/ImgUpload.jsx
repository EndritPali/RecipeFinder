import { Upload, Input, Form } from 'antd';

const { Dragger } = Upload;

export default function FormImageUpload({ form }) {
    const uploadProps = {
        name: 'image',
        multiple: false,
        action: 'http://127.0.0.1:8000/api/upload-image',
        headers: { Accept: 'application/json' },
        onChange(info) {
            const { status } = info.file;
            if (status === 'done' && info.file.response?.url) {
                form.setFieldsValue({ image: info.file.response.url });
            } else if (status === 'error') {
                console.error('Image upload failed:', info.file.response || 'Unknown error');
            }
        },
        beforeUpload(file) {
            const isImage = file.type.startsWith('image/');
            const isLt2M = file.size / 1024 / 1024 < 2;
            if (!isImage) {
                console.error('Only images allowed!');
                return false;
            }
            if (!isLt2M) {
                console.error('Image must be < 2MB!');
                return false;
            }
            return true;
        },
    };

    return (
        <>
            <div className="form-img-upload">
                <Dragger {...uploadProps} listType='picture' maxCount={1} name='image'>
                    <p>Click or drag to upload image</p>
                </Dragger>
            </div>
            <Form.Item name="image" noStyle>
                <Input type="hidden" />
            </Form.Item>
        </>
    );
}
