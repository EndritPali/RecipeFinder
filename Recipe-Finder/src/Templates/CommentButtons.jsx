import { Button, Tooltip } from "antd";
import { UserOutlined, EditOutlined, DeleteOutlined, } from '@ant-design/icons';

export default function CommentButtons({ onEdit, onDelete, isOwner, isAdmin }) {
    const canEdit = isOwner;
    const canDelete = isOwner || isAdmin;

    return (
        <>
            {canEdit ? (
                <Button className="btn-edit" onClick={onEdit}>
                    <EditOutlined />
                </Button>
            ) : (
                <Tooltip title="You cannot edit this comment">
                    <Button className="btn-edit" disabled>
                        <EditOutlined />
                    </Button>
                </Tooltip>
            )}

            {canDelete ? (
                <Button className="btn-delete" onClick={onDelete}>
                    <DeleteOutlined />
                </Button>
            ) : (
                <Tooltip title="You cannot delete this comment">
                    <Button className="btn-delete" disabled>
                        <DeleteOutlined />
                    </Button>
                </Tooltip>
            )}
        </>
    );
}
