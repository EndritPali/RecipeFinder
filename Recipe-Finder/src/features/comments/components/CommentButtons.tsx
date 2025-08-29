import { Button, Tooltip } from "antd";
import { EditOutlined, DeleteOutlined, } from '@ant-design/icons';

export default function CommentButtons({ onEdit, onDelete, isOwner, isAdmin }: any) {
    const canEdit = isOwner;
    const canDelete = isOwner || isAdmin;

    return (
        <>
            {canEdit ? (
                <Button className="btn-edit" onClick={onEdit}>
                    <EditOutlined />
                </Button>
            ) : (
               ''
            )}

            {canDelete ? (
                <Button className="btn-delete" onClick={onDelete}>
                    <DeleteOutlined />
                </Button>
            ) : (
              ''
            )}
        </>
    );
}
