import '../Scss/Mobile-Footer.scss'


export default function MobileFooter() {
    return (
        <>
            <div className="mobile-footer">
                <div className="mobile-footer__top">
                    <i className="fas fa-globe"></i>
                </div>
                <div className="mobile-footer__bottom">
                    <div className="mobile-footer__bottom-left">
                        <i className="fas fa-house"></i>
                        <i className="far fa-heart"></i>
                    </div>
                    <div className="mobile-footer__bottom-right">
                        <i className="far fa-bookmark"></i>
                        <i className="far fa-user"></i>
                    </div>
                </div>
            </div>
        </>
    )
}