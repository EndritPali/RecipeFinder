import '../Scss/MobileFooter.scss'
import { Link } from 'react-router-dom'

export default function MobileFooter() {
    return (
        <>
            <div className="mobile-footer">
                <div className="mobile-footer__top">
                    <Link to={'/admin'}>
                        <i className="fas fa-globe"></i>
                    </Link>
                </div>
                <div className="mobile-footer__bottom">
                    <div className="mobile-footer__bottom-left">
                        <Link to={'/'}>
                            <i className="fas fa-house"></i>
                        </Link>
                        <i className="far fa-heart"></i>
                    </div>
                    <div className="mobile-footer__bottom-right">
                        <i className="far fa-bookmark"></i>
                        <Link to={'/admin/users'}>
                            <i className="far fa-user"></i>
                        </Link>
                    </div>
                </div>
            </div>
        </>
    )
}