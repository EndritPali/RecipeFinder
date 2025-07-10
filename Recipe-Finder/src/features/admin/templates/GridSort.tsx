import { Card, Row, Col, Avatar, Pagination, Spin } from 'antd';
import { EditOutlined, DeleteOutlined, UserOutlined } from '@ant-design/icons';
import { GridSortProps, CardDescriptionProps } from '@/types/admin';

export default function GridSort({ data, onEdit, onDelete, pagination, loading }: GridSortProps) {
  const { Meta } = Card;

  const handleEdit = (item: any) => () => {
    onEdit(item);
  };

  const getInitials = (name?: string) => {
    if (!name) return '';
    const parts = name.trim().split(' ');
    if (parts.length === 1) return parts[0][0].toUpperCase();
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
  };

  const handleDelete = (item: any) => () => {
    if (onDelete) onDelete(item.key);
  };

  return (
    <Spin spinning={loading}>
      <Row gutter={[16, 16]} className="grid-view">
        {data.map((item: any) => (
          <Col key={item.key} xs={24} sm={12} md={8} lg={6}>
            <Card
              className="recipe-card"
              hoverable
              cover={
                item.username && item.email ? (
                  <div style={{ display: 'flex', justifyContent: 'center', paddingTop: '1rem' }}>
                    <Avatar size={96}>
                      {item.username ? getInitials(item.username) : <UserOutlined />}
                    </Avatar>
                  </div>
                ) : (
                  <img
                    alt={item.recipetitle}
                    src={item.image}
                  />
                )
              }
              actions={[
                <EditOutlined key="edit" onClick={handleEdit(item)} />,
                <DeleteOutlined key="delete" onClick={handleDelete(item)} />,
              ]}
            >
              <Meta
                title={item.recipetitle || item.username}
                description={
                  <CardDescription item={item} />
                }
              />
            </Card>
          </Col>
        ))}
      </Row>
      {pagination &&
        <div className='pagination-wrap'>
          <Pagination
            {...pagination}
            showSizeChanger
            showTotal={(total) => `Total ${total} items`}
          />
        </div>
      }
    </Spin>
  );
}

function CardDescription({ item }: CardDescriptionProps) {
  return (
    <>
      {item.category && <p className="category">{item.category}</p>}
      {item.shortdescription && <p className="description">{item.shortdescription}</p>}

      {item.email && <p className="email"><strong>Email:</strong> {item.email}</p>}
      {item.email && <p className="role">{item.role}</p>}

      {item.createdby && <p className="author"><strong>By:</strong> {item.createdby}</p>}
      {item.date && <p className="date"><strong>Created:</strong> {item.date}</p>}
    </>
  );
}