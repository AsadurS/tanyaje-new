@extends('newtheme.layouts.main')
@section('meta_for_share')
    <meta property="og:title" content="{{ trans('labels.app_name') }}">
    <meta property="og:url" content="https://tanyaje.com.my/">
    <meta property="og:image" content="{{asset('new/images/logo4.png')}}">
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="400" />
    <meta property="og:image:type" content="image/png">
    <meta property="og:description" content="Tanya-Je translated as “Just Ask” is the first most advanced online digital automotive classified in South East Asia.">
@endsection
@section('content')

<!-- START content_part-->
<section id="content_part">

    <!-- START aboutUsArea -->
    <section class="privacyPolicyArea">
        <div class="container">
            <div class="left-pane">
                <ul>
                    <li>
                        <div class="menu-item">
                            <a href="{{ route("about_us") }}">
                                About Us
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="menu-item">
                            <a href="{{ route('contact_us') }}">
                                Contact Us
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="menu-item">
                            <a href="{{ route('term_of_service') }}">
                                Term Of Service
                            </a>
                        </div>
                    </li>
                    <li class="active">
                        <div class="menu-item">
                            <a href="{{ route('privacy_policy') }}">
                                Privacy Policy
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="right-pane">
                <div class="heading">
                    Privacy Policy
                </div>
                <div>
                    <p>
                        Please read below to understand your rights and how we use the information we gather
                    </p>
                    <p>
                        Effective Date: 1 July 2020
                    </p>
                    <p>
                        This Privacy Notice (“Notice”) applies to this website or mobile application and the websites or mobile applications of Tanya-Je, and its subsidiaries and affiliates (collectively “we”, “us” or “our”) that display this Notice (each a “Site” and collectively, the “Sites”). By visiting the Sites, you are consenting to our collection, use, disclosure, retention, and protection of information about you and devices you use as described in this Notice.
                    </p>
                    <p>
                        This Notice only applies to the Sites and does not apply to information collected or received by other means.
                    </p>
                    <p>
                        Our Sites are not directed to children under the age of 18. We do not knowingly collect personal information from children under age 18. This Notice does not apply to anonymized or aggregated data that does not allow us or third parties to identify or contact you.
                    </p>
                    <p>
                        <b>1. What Information Does Tanya-Je Gather?</b><br/>
                        <u>Information You Give Us.</u> We may collect and retain any information from you or your devices provided to us when you visit a Site, including when you:
                        <br/><br/>
                        <div>
                            <ul>
                                <li>
                                    ●	Use our service or mobile application;
                                </li>
                                <li>
                                    ●	Register for an account;
                                </li>
                                <li>
                                    ●	List, modify, delete a car (for sale) ad
                                </li>
                                <li>
                                    ●	Perform a search
                                </li>
                                <li>
                                    ●	Click to reveal seller info
                                </li>
                                <li>
                                    ●	Request an offer
                                </li>
                                <li>
                                    ●	Inquire about a vehicle listed for sale or another product or service listed on our website
                                </li>
                                <li>
                                    ●	Communicate with us, such as to provide feedback, request support, or ask for additional information; and
                                </li>
                                <li>
                                    ●	Subscribe to content we offer, such as newsletters, alerts, etc.
                                </li>
                            </ul>
                        </div>
                    </p>
                    <p>
                        We may also collect additional information such as your name, physical address, email address and phone number, which you may provide when you sign up for a new account, newsletters, complete our “Feedback” form, or use a social media tool. We may use this information to provide you with the products, services or tools which you requested.
                    </p>
                    <p>
                        When you list a car for sale with Tanya-Je, we require information about the car and how you can be contacted by potential buyers (including information like your phone number and email address).
                    </p>
                    <p>
                        <u>Social Media</u>. You may use social networks or other online services to access our Sites. When you do so, information from those services may be made available to us. By associating a social network account with our Sites, you agree that we may access and retain that information in accordance with the policies of the social network or other online service and this Notice. For example, we may be able to access account or profile information that you have provided to the social network or information about your interactions with the social network to make information available to us (such as when commenting on a blog post or using a sign-on service, such as Facebook Connect).
                    </p>
                    <p>
                        <u>Automatically Collected Information.</u> We may collect information automatically when you visit our Sites or use our mobile applications, such as:
                        <br/><br/>
                        <div>
                            <ul>
                                <li>
                                    ●	your IP address; the type of browser, devices and operating systems you use;
                                </li>
                                <li>
                                    ●	identifiers associated with the device(s) you use to access our Sites;
                                </li>
                                <li>
                                    ●	the pages you visit, vehicles you view or configure, and the features you use, including dates and times;
                                </li>
                                <li>
                                    ●	if you navigated from or navigate to another website, the address of that website; and
                                </li>
                                <li>
                                    ●	information regarding your internet service provider.
                                </li>
                            </ul>
                        </div>
                    </p>
                    <p>
                        In addition, we may collect information about your activities on our Sites via first and third-party cookies, clear GIFs or web beacons, or through other identifiers or technologies, including similar technologies as they may evolve over time. We refer to these technologies collectively as Metrics Tools.
                    </p>
                    <p>
                        We may allow third parties to use Metrics Tools on our Sites. The information collected by Metrics Tools may be shared with and used by us, by others acting on our behalf, or by third parties subject to their own privacy policies. Information collected by Metrics Tools may be used on this Site or on other websites or services, including those that may not be operated by us.
                    </p>
                    <p>
                        <u>Email.</u> We may collect information regarding the effectiveness of our email and other communications with you. For example, we may know if you follow a link in an email we send to you.
                    </p>
                    <p>
                        <u>Mobile.</u> We may collect session and geolocation information from your mobile device. Geolocation information includes data such as your device’s physical location and may include GPS-based, WiFi-based or cell-based location information.
                    </p>
                    <p>
                        <u>Information from Other Sources.</u> We may obtain information about you from affiliates, partners, automobile dealers and other third parties. This information may include information about your use of this Site or our services, your use of other websites, your interactions with or purchases from automobile dealers, your interests and preferences and other information about you or your household. We may combine the information we obtain from third parties with information that we or our affiliates have collected about you.
                    </p>
                    <p>
                        <b>2. How Does Tanya-Je Use Information About Me?</b>
                        <br/>
                        We and others acting on our behalf may use the information that we collect or receive to operate our business, including our Sites, as described below:
                    </p>
                    <p>
                        <u>Operate and Support our Sites and Services.</u> We use the information that we gather in order to operate our Sites and our services. For example, we may use the information that we collect or receive to provide support and assistance that you request or to diagnose or address technical problems in the operation of our Sites or any of our services. If you establish an account with us, we may use information about you to manage or support your account. We may identify your use of our Sites across devices, and we may use information we collect from you and receive from others to optimize your use of the Sites and our services as you use different devices.
                    </p>
                    <p>
                        <u>Improving and Evolving our Services.</u> We constantly evaluate and improve our Sites and services, including developing new products or services and use the information we gather to do so.
                    </p>
                    <p>
                        <u>To Allow You to Connect with Dealers and Vehicle Manufacturers.</u> When you use our service, and after you provide your contact information to request offers or information, we may use the information you provide us to connect you with a limited number of automobile dealers and vehicle manufacturers. These dealers and vehicle manufacturers may provide you with a savings certificate, offers and/or incentives for new or used vehicles or other products or services. When you choose to share your contact information with dealers (i.e., providing your mobile phone for texts instead of using our text masking service), your direct interactions with those dealers will not be controlled by this Notice. You should review the applicable notices of those dealers to understand how they may use your information.
                    </p>
                    <p>
                        <u>To Allow You to Connect with Lending Institutions.</u> Our Sites and services may permit you to provide information relevant to assessing your creditworthiness or designed to facilitate financing (for example, in connection with a potential vehicle lease or purchase). Your direct interactions with lending institutions will not be controlled by this Notice. You should review the applicable notices of those lending institutions to understand how they may use your information.
                    </p>
                    <p>
                        <u>Advertising and Promotions.</u> We may use the information we gather to offer, provide, or personalize products and services from us and third parties. For example, we may customize content, advertising, promotions and incentives to reflect your preferences, interests, or prior interactions with us and others.
                    </p>
                    <p>
                        <u>Other Contacts.</u> We may contact you through telephone, text, or chat for other purposes, as permitted by law.
                    </p>
                    <p>
                        Other ways we may use your personal information include:
                        <br/><br/>
                        <div>
                            <ul>
                                <li>
                                    ●	send e-mail newsletters and other information to you as you request and to notify you from time to time about services offered by the Site or other entities associated with the Site,
                                </li>
                                <li>
                                    ●	profile and/or analyse you using and accessing the Website and your pattern and manner of usage,
                                </li>
                                <li>
                                    ●	customise the advertising and content you see, to fulfil your requests for certain products and services, and to contact you about specials and new products, whether directly or through our partners or affiliates but for the sole purpose of fulfilling the functions above
                                </li>
                                <li>
                                    ●	research, develop, and improve Tanya-Je services,
                                </li>
                                <li>
                                    ●	conduct surveys to determine use and satisfaction with Tanya-Je services,
                                </li>
                                <li>
                                    ●	generate statistics in relation to Tanya-je
                                </li>
                                <li>
                                    ●	promote and market special offers and other services to you
                                </li>
                            </ul>
                        </div>
                    </p>
                    <p>
                        We will not, however, share any such Personal Information with other entities except for such entities that have been authorised to carry out specific services for the Site.
                    </p>
                    <p>
                        The Site shall preserve the contents of any communications which you send if the Site is of the view that there is a legal requirement for doing so. Your communications may be monitored for purposes of trouble-shooting or maintenance purposes.
                    </p>
                    <p>
                        This Notice only addresses our own information practices. This Notice does not apply to information you share with third parties, including but not limited to dealers and lending institutions, even if we link to those third parties from a Site. These third parties may have their own privacy policies governing their use of information that you can access from their websites. Our services may be offered through third party websites or mobile applications (“Partner Sites”). This Notice does not apply to Partner Sites, unless otherwise indicated on the Partner Site.
                    </p>
                    <p>
                        Please note that other parties may collect personally identifiable information about your online activities over time and across different websites when you use our Site.
                    </p>
                    <p>
                        <b>3. When Does Tanya-Je Share Information?</b>
                        <br/>
                        We only share information about you as described or permitted by this Notice, unless you consent to other sharing. Individuals and/or organisations to which we may disclose personal information include:
                        <br/><br/>
                        <div>
                            <ul>
                                <li>
                                    ●	Tanya-Je staff and Tanya-Je bodies corporate
                                </li>
                                <li>
                                    ●	the public if you advertise with Tanya-Je or use publicly available Communications Services
                                </li>
                                <li>
                                    ●	Individuals and organisations that advertise with Tanya-Je if you submit an enquiry with Tanya-Je. If you do not want Tanya-Je to disclose your personal information to these individuals and organisations, please do not submit enquiries.
                                </li>
                                <li>
                                    Depending on your enquiry these may include:
                                    <br/>
                                    <div>
                                        <ul style="padding-left:30px;">
                                            <li>
                                                ○	private advertisers
                                            </li>
                                            <li>
                                                ○	licensed car dealers
                                            </li>
                                            <li>
                                                ○	car brokers
                                            </li>
                                            <li>
                                                ○	operators of Linked Sites
                                            </li>
                                            <li>
                                                ○	financial service providers
                                            </li>
                                            <li>
                                                ○	insurance service providers
                                            </li>
                                            <li>
                                                ○	vehicle inspection service providers
                                            </li>
                                            <li>
                                                ○	outsourced service providers who assist Tanya-Je to provide its services including:
                                            </li>
                                            <li>
                                                ○	information technology providers
                                            </li>
                                            <li>
                                                ○	marketing and market research advisers
                                            </li>
                                            <li>
                                                ○	professional advisers
                                            </li>
                                            <li>
                                                ○	resellers of Tanya-Je services
                                            </li>
                                            <li>
                                                ○	organisations involved in a sale/transfer of Tanya-Je assets, business, or shares
                                            </li>
                                            <li>
                                                ○	government and regulatory authorities as required by law
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </p>
                    <p>
                        Except as provided herein and as required by law, your Personal Information will not be made available to third parties without your consent.
                    </p>
                    <p>
                        <u>Hyperlinks</u>
                    </p>
                    <p>
                        When you are on the Site, you could be directed to other sites that are beyond our control including hyperlinks to advertisers, merchants, sponsors, and content partners.
                    </p>
                    <p>
                        You acknowledge that when you click on a hyperlink that leaves the Site, the site you are directed to is not within our control, and you acknowledge that the Site shall not be responsible for those sites or any damages or losses incurred by you resulting thereto.
                    </p>
                    <p>
                        <b>4. What Choices Do I Have Regarding My Information?</b>
                        <br/>
                        You may limit and control the information provided to us in several ways. You may not be able to use all features of our Sites if you limit the information you share with us.
                    </p>
                    <p>
                        For example, you can choose not to access our Sites through your social media account. You may also be able to limit the information provided to us by third party social media providers by altering your privacy settings with those providers. You may unsubscribe from promotional emails from us by following the unsubscribe link included in each such email.
                    </p>
                    <p>
                        Your browser and your device may provide you the option to limit the use of cookies or other Metrics Tools. You should consult documentation for your browser or device for more information. Your mobile device may have settings that allow you to prevent sharing geolocation information with us. You should consult your mobile device’s settings or help documentation for more information about exercising these options.
                    </p>
                    <p>
                        Tanya-Je uses Google Analytics, which helps Tanya-Je better understand its audience and target communications and advertisements based on demographic or interest-based information. You may learn more about opting out of certain Google advertising initiatives here and here. You may also be able to opt out of certain targeted behavioral advertising via the Network Advertising Initiative’s opt-out.
                    </p>
                    <p>
                        Please note that you may still receive advertising even after opting out, but that advertising may not be tailored to you or your interests.
                    </p>
                    <p>
                        <b>5. How Can I Access and Update My Information?</b>
                        <br/>
                        If you are a registered user on Tanya-Je.com, you may access, update and change certain information we have collected about you by accessing the “My Account” tab after signing into your account on www.Tanya-Je.com.
                    </p>
                    <p>
                        <b>6. How Does Tanya-Je Address Malaysia Privacy Rights?</b>
                        <br/>
                        If you choose not to provide personal information when requested, the Site may not be able to provide you with the full range of services. You are given the opportunity to 'opt-out' of having your Personal Information used for purposes not directly related to the Website at the point where we ask for it.
                    </p>
                    <p>
                        If you do not wish to have information on you used in any of the aforesaid manner, you can e-mail us through our Feedback form or to <a href="mailto:info@tanyaje.com.my" style="color: blue"><u>info@tanyaje.com.my</u></a>.
                    </p>
                    <p>
                        If you do not want to receive e-mail or other mail from us, you can click on the unsubscribe link in our emails to remove your email address from our mailing list.
                    </p>
                    <p>
                        <u>Request not to use your personal information</u>
                        <br/>
                        If you request that the Site does not use your Personal Information in any particular manner, we will adopt all reasonable measures to observe your request but we may still use or disclose that Personal Information if we believe that the use and disclosure is reasonably necessary under the governing laws.
                    </p>
                    <p>
                        You may also designate an authorized agent to make a request on your behalf. Please note that we may take steps to verify your identity before granting you access to information or acting on your request to exercise your rights, as required or permitted by law. We may limit our response to your exercise of the above rights as permitted by law.
                    </p>
                    <p>
                        <u>Data Sharing for Direct Marketing Purposes</u>
                        <br/>
                        Residents of Malaysia may request a list of all third parties to which we have disclosed certain personal information (as defined by Malaysia law) during the preceding year for those third parties’ direct marketing purposes. If you are a Malaysia resident and want such a list, please contact us via the Contact section provided below.
                    </p>
                    <p>
                        <u>Do Not Track Notice</u>
                        <br/>
                        At this time, there is no worldwide uniform or consistent industry standard or definition for responding to, processing, or communicating Do Not Track signals. Thus, like many other websites and online services, our Sites are currently unable to respond to Do Not Track Signals.
                    </p>
                    <p>
                        <u>International visitors</u>
                        <br/>
                        This website is intended for use by visitors within Malaysia. Please be aware that:
                        <br/><br/>
                        <div>
                            <ul>
                                <li>
                                    1.	Our databases are stored on servers and storage devices located in Malaysia, Singapore, and various other parts of the world,
                                </li>
                                <li>
                                    2.	Your information (including personal data) may be transferred to these locations for processing and storage,
                                </li>
                                <li>
                                    3.	These locations may not guarantee the same level of protection for personal data as the locality or country in which you reside. By using our website, you expressly agree to our collection, use, disclosure, and transfer of your information (including personal data) for the purposes identified herein, and you consent to the transfer of such information outside of your country.
                                </li>
                            </ul>
                        </div>
                    </p>
                    <p>
                        <u>About security and personal Information</u>
                        <br/>
                        The Site uses its best endeavours to store all Personal Information on servers with restricted access, and all electronic storage and transmission of Personal Information are secured with appropriate security technologies. Not to withstand the foregoing, the Site cannot guarantee that such precautions would render the Site and its servers immune to security breaches.
                    </p>
                    <p>
                        <b>7. How Does Tanya-Je Handle Privacy Disputes?</b>
                        <br/>
                        By using this Site, you agree that any dispute arising out of or relating to the Sites, the Sites’ content or the services or materials made available on the Sites, or regarding information collected or shared about you, is subject to the Choice of Law, Venue, and Class Action Waiver provisions in our Terms of Service.
                    </p>
                    <p>
                        <b>8. How Does Tanya-Je Protect the Security of the Personal Information it Collects?</b>
                        <br/>
                        We use administrative, technical, and physical security designed to safeguard personal information in our possession. We cannot guarantee the security of the information that we collect and store. If you believe that information about you has been subject to unauthorized disclosure, please let us know by emailing <a href="mailto:info@tanyaje.com.my" style="color: blue"><u>info@tanyaje.com.my</u></a>
                    </p>
                    <p>
                        <b>9. How Does Tanya-Je Provide Updates to this Notice?</b>
                        <br/>
                        When we make material changes to this Notice, we will update this web page and change the Effective Date listed above.
                    </p>
                    <p>
                        <b>10. How Can Tanya-Je be Contacted Regarding Privacy Issues?</b>
                        <br/>
                        You can contact us with questions or comments about our privacy practices or this Notice by emailing us at <a href="mailto:info@tanyaje.com.my" style="color: blue"><u>info@tanyaje.com.my</u></a>  or you can contact us at:
                        <br/><br/>
                        Tanya-Je,<br/>
                        No. 30 & 32, Jalan SS 26/11,<br/>
                        Taman Mayang Jaya, 47301,<br/>
                        Petaling Jaya, Selangor
                    </p>

                </div>
            </div>
        </div>
    </section>
    <!-- END aboutUsArea -->

</section>
<!-- END content_part-->

@endsection