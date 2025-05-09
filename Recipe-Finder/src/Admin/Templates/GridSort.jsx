import { Card, Row, Col } from 'antd';
import { EditOutlined, DeleteOutlined } from '@ant-design/icons';

export default function GridSort({ data, onEdit, onDelete }) {
  const { Meta } = Card;

  const handleEdit = (item) => () => {
    onEdit(item);
  };

  const handleDelete = (item) => () => {
    if (onDelete) onDelete(item.key);
  };

  return (
    <Row gutter={[16, 16]} className="grid-view">
      {data.map(item => (
        <Col key={item.key} xs={24} sm={12} md={8} lg={6}>
          <Card
            className="recipe-card"
            hoverable
            cover={
                <img 
                  alt={item.recipetitle || item.username} 
                  src={item.image || item.profilePic} 
                />
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
  );
}

function CardDescription({ item }) {
  return (
    <>
      {item.category && <p className="category">{item.category}</p>}
      {item.shortdescription && <p className="description">{item.shortdescription}</p>}
      
      {item.email && <p className="role">{item.role}</p>}
      {item.email && <p className="email"><strong>Email:</strong> {item.email}</p>}
      
      {item.createdby && <p className="author"><strong>By:</strong> {item.createdby}</p>}
      {item.date && <p className="date"><strong>Created:</strong> {item.date}</p>}
    </>
  );
}