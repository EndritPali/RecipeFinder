import { Result, Button } from 'antd';
import { Link } from 'react-router-dom';
import '@/Scss/ForbiddenPage.scss'

export default function ForbiddenPage() {
    return (
        <Result
            status="403"
            title="403"
            subTitle="Sorry, you are not authorized to access this page."
            className='forbidden'
            extra={
                <Button type="primary">
                    <Link to="/">Back Home</Link>
                </Button>
            }
        />
    );
}
