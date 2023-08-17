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
    <section class="termOfServiceArea">
        <div class="container">
            <div class="left-pane">
                <ul>
                    <li>
                        <div class="menu-item">
                            <a href="{{ route('about_us') }}">
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
                    <li class="active">
                        <div class="menu-item">
                            <a href="{{ route('term_of_service') }}">
                                Term Of Service
                            </a>
                        </div>
                    </li>
                    <li>
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
                    Term Of Service
                </div>
                <div>
                    <p>
                        By accessing or using this website, any of its pages and/or any of the services referenced herein, you accept and agree to be bound by the Terms of Service set forth below.
                    </p>
                    <p>
                        <b>ACCEPTANCE OF TERMS</b><br/>
                        Tanya-Je Sdn. Bhd. (“Tanya-Je,” “we,” “us,” or “our”) provides users with an online automotive information and communications platform, as well as related services that can be accessed from or through this website (collectively, “Services”). Please carefully read these Terms of Service before using the Services. By accessing or using the Services, including this website, you accept and agree to be bound by these Terms of Service (“Terms of Service”) and all applicable laws, rules, and regulations associated with your use of the Services. If you do not agree to the Terms of Service, you are not authorized to use this website or the Services. These Terms of Service also apply to any co-branded or framed version of this website.
                    </p>
                    <p>
                        Your use of certain materials and features of this website and/or the Services may be subject to additional terms and conditions which are incorporated herein by reference and become part of the Terms of Service. By using those materials and features, you also agree to be bound by such additional terms and conditions. Unless explicitly stated otherwise, any new features that
                        augment or enhance the current Services shall be subject to the Terms of Service.
                    </p>
                    <p>
                        This website is controlled and operated by Tanya-Je from its offices within the United States. Tanya-Je makes no representation that information or materials available on this website are appropriate or available for use in other locations, and access to this website from territories where its contents are illegal is prohibited. Those who choose to access this website from other locations do so at their own initiative and are responsible for compliance with applicable local laws.
                    </p>
                    <p>
                        <b>USER CONDUCT</b><br/>
                        You are authorized by Tanya-Je to access and use the Services, including the information on this website, solely for your personal, non-commercial use provided that you are at least 18 years of age. The information and materials displayed on this website may not otherwise be copied, transmitted, displayed, distributed, downloaded, licensed, modified, published, posted, reproduced, used, sold, transmitted, used to create a derivative work, or otherwise used for commercial or public purposes without Tanya-Je’s express prior written consent. Any use of data mining, robots or similar data gathering or extraction tools or processes in connection with this website, and any reproduction or circumvention of the navigational structure or presentation of this website or its content, is strictly prohibited. You agree not to use the Services, including this website, for any unlawful purpose.
                    </p>
                    <p>
                        In order to access certain features of this website you may be required to register. You are responsible for maintaining the confidentiality of your password and account and are fully responsible for all activities that occur under your password or account. You agree to immediately notify Tanya-Je in writing of any unauthorized use of your password or account or any other breach of security, and ensure that you exit from your account at the end of each session. Tanya-Je is not liable for any loss or damage arising from your failure to comply with this section. You agree not to modify the Services or use modified versions of the Services (except if modified by Tanya-Je), including for the purpose of obtaining unauthorized access to the Services. You agree not to access the Services by any means other than through the interface that is provided by Tanya-Je for use in accessing the Services.
                    </p>
                    <p>
                        <b>TELEPHONIC COMMUNICATIONS WITH TANYA-JE AND PARTICIPATING DEALERS</b><br/>
                        You verify that any contact information provided to Tanya-Je or a participating dealer, including, but not limited to, your name, mailing address, email address, your residential telephone number, and/or your mobile telephone number, is true and accurate. You verify that you are the current subscriber or owner of any telephone number that you provide.
                    </p>
                    <p>
                        You agree to indemnify, defend and hold Tanya-Je and participating dealers harmless from and against any and all claims, losses, liability, costs and expenses (including reasonable attorneys’ fees) arising from your voluntary provision of contact information (including a telephone number) that is not your own, and/or from your violation of any federal, state or local law, regulation or ordinance.
                    </p>
                    <p>
                        You acknowledge that by voluntarily providing your telephone number(s) to Tanya-Je or participating dealers, you expressly agree to be contacted at the email and contact number provided by you. You acknowledge that we may add to or withdraw dealers from our list at any time, and you consent to be contacted, as explained above, by these dealers notwithstanding the date of the start of your relationship with Tanya-Je. Your consent to be contacted applies to participating dealers that may have existed at the start of your relationship with Tanya-Je and that may thereafter be added to our participating dealers list. You acknowledge that you may incur a charge for calls or text messages by your telephone carrier and that neither Tanya-Je nor participating dealers are responsible for these charges.
                    </p>
                    <p>
                        <b>PRIVACY</b><br/>
                        Tanya-Je is committed to respecting your privacy and protecting your personally identifiable information. Upon your request, Tanya-Je shares the information you enter on this website with participating dealers and/or buyers in order to provide you with the information you requested, and with other service providers associated with the Services. Tanya-Je account data and certain other information about you and that you enter and/or we collect through your use of this website are subject to the Tanya-Je Privacy Policy at http://www.tanyaje.com.my/privacy. You understand that through your use of the Services, including this website, you consent to the collection and use (as set forth in the Tanya-Je Privacy Policy) of this information. Please review our Privacy Policy for further information on our data collection and use practices.
                    </p>
                    <p>
                        <b>SERVICES</b><br/>
                        All information provided on this website is for informational purposes only. Information displayed through the Services related to any virtual vehicle you may configure, such as dealer cost, factory invoice, market average, Tanya-Je Average, Tanya-Je Estimate, either reflects or is based on available data relevant to your virtual vehicle and does not reflect a dealer's price for an actual vehicle consistent with your preferences. Neither the accuracy of information provided on this website, nor the availability, quality, or safety of vehicles, is guaranteed or controlled by Tanya-Je, and Tanya-Je assumes no responsibility for the foregoing. You agree that any reliance on the information on this website is at your own risk. Any discrepancies or mistakes made regarding vehicle availability, condition, pricing, and the like are not the responsibility of Tanya-Je and should be directed to the dealer or third party vendor. You are encouraged to thoroughly review any documents you are asked to sign at the time of purchase or lease of a vehicle or of other products or services. We do invite you to bring to our attention any material on our website that you believe to be inaccurate; please forward a copy of such material and your reasons for your belief to info@tanyaje.com.my.
                    </p>
                    <p>
                        By using the Services, you acknowledge and agree that participating dealers may not have in inventory a new vehicle that exactly matches any virtual vehicle you may configure on the website, and certain models or configurations may not be available. Your dealer will confirm vehicle availability, including available options and colors, from actual inventory. Each dealer sets and controls its own pricing. You may negotiate the purchase price directly with the dealer, and Tanya-Je plays no role in that negotiation. The savings information communicated directly to you by a selling dealer for a vehicle consistent with the preferences expressed in any virtual vehicle you may configure may change if the actual in-stock vehicle that you choose to buy differs from the configuration of your virtual vehicle. Used vehicles are subject to prior sale.
                    </p>
                    <p>
                        For the avoidance of doubt, Tanya-Je is solely a research and communications platform. Tanya-Je is not a vehicle seller, dealer, broker or agent for vehicle sellers or dealers, nor a provider of, or broker or agent for, other automotive-related products or services offered by third parties. Tanya-Je receives a fee from the participating dealers or third-party service providers in connection with the Services.
                    </p>
                    <p>
                        <b>NO COMMERCIAL USE OF SERVICES</b><br/>
                        You agree not to reproduce, duplicate, copy, sell, trade, resell or exploit for any commercial purposes, any portion or use of, or access to, the Services.
                    </p>
                    <p>
                        <b>RIGHT TO DENY ACCESS AND TO MODIFY THE SERVICES</b><br/>
                        Tanya-Je reserves the right to deny use of, or access to, the Services to you and/or anyone for any or no reason. Tanya-Je also reserves the right at any time and from time-to-time to modify or discontinue, temporarily or permanently, the Services (or any part thereof) with or without notice. You agree that Tanya-Je shall not be liable to you or to any third party for any modification, suspension or discontinuance of the Services (or any part thereof).
                    </p>
                    <p>
                        <b>INDEMNIFICATION</b><br/>
                        You will indemnify, defend and hold harmless Tanya-Je and its subsidiaries, affiliates, partners, officers, directors, employees, and agents (collectively, “Tanya-Je Entities”) from all claims, whether actual or alleged (collectively, “Claims”), that arise out of or in connection with a breach of these Terms of Service, use of the Services, including any content you submit, post, transmit, modify or otherwise make available through the Services, and/or any violation of law and/or the rights of any third party. You are solely responsible for defending any Claim against a Tanya-Je Entity, subject to such Tanya-Je Entity’s right to participate with counsel of its own choosing, at its own expense, and for payment of all judgments, settlements, damages, losses, liabilities, costs, and expenses, including reasonable attorneys’ fees, resulting from all Claims against a Tanya-Je Entity, provided that you will not agree to any settlement that imposes any obligation or liability on a Tanya-Je Entity without its prior express written consent.
                    </p>
                    <p>
                        <b>DISCLAIMER OF WARRANTIES</b><br/>
                        The services, including all information and content on or otherwise related in any way to the services, this website or any third-party website, product, or service linked to or from this website, are provided “as is” without warranty of any kind, either express or implied, including the implied warranties of merchantability, fitness for a particular purpose, timeliness, and noninfringement. In addition, tanya-je does not warrant against defects in any vehicle, and does not make any warranty of any kind, either express or implied, including representations, promises, or statements as to the condition, fitness, or merchantability of any vehicle or service. No advice or information, whether oral or written, obtained from Tanya-Je or through or linked from the services shall create any warranty express or implied. Tanya-Je is not responsible for making repairs to any vehicle. If you have complaints or concerns about defects or repairs, please contact the dealer, seller, or manufacturer directly.
                    </p>
                    <p>
                        <b>LIMITATION OF LIABILITY; WAIVER</b><br/>
                        In no event shall the Tanya-Je entities be liable for any direct, indirect, incidental, special, consequential, or exemplary damages, including damages for loss of profits, goodwill, use of data, information, and/or content, or other intangible losses arising out of, or in connection with the services, including all information and content on or otherwise related in any way to this website or any third-party website, product, or service linked to or from this website, or any vehicle referenced therein. Without limiting the foregoing, any information and content downloaded or otherwise obtained through the use of the services is accessed at your own discretion and risk, and you will be solely responsible for and hereby waive any and all claims and causes of action with respect to any damage to your computer system, internet access, download or display device, or loss of data that results from the download of any such information and content. Your sole and exclusive remedy for dissatisfaction with any service and/or this website is to stop using the service and/or website.
                    </p>
                    <p>
                        You hereby irrevocably waive any claim (whether for injury, illness, damage, liability and/or cost) against the tanya-je entities arising out of your use of or inability to use, or in connection with, the services, including any information and content on this website or any third-party website, product, or service linked to or from this website, including any content you provide to third parties (including personally identifiable information).
                    </p>
                    <p>
                        <b>EXCLUSIONS AND LIMITATIONS</b><br/>
                        Some jurisdictions do not allow the exclusion of certain implied warranties, or the limitation or exclusion of liability for incidental or consequential damages, so the above exclusions and limitations may not apply to you.
                    </p>
                    <p>
                        <b>NOTICE</b><br/>
                        Tanya-Je may provide you with notices by email, regular mail, SMS, MMS, text message, postings on the Services/website, or other reasonable means now known or hereafter developed. You acknowledge and agree that Tanya-Je will have no liability associated with or arising from your failure to maintain and supply Tanya-Je with accurate contact information about yourself, including your failure to receive important information and updates about the Services or this website.
                    </p>
                    <p>
                        <b>TRADEMARK INFORMATION AND INTELLECTUAL PROPERTY</b><br/>
                        You agree that all of Tanya-Je’s trademarks, trade names, service marks, logos, brand features, and product and Service names are trademarks and the property of Tanya-Je, and that you will not display or use any of the foregoing without Tanya-Je's prior written approval in each instance.
                    </p>
                    <p>
                        You agree that the Services contain proprietary information protected by applicable intellectual property and other laws in favor of Tanya-Je. You further agree that content and information presented to you through the Services is protected by copyrights, trademarks, service marks, patents and/or other proprietary rights and laws.
                    </p>
                    <p>
                        <b>COPYRIGHT OR INTELLECTUAL PROPERTY COMPLAINTS</b><br/>
                        Tanya-Je respects the intellectual property of others. If you believe that your work has been copied in a way that constitutes copyright infringement, or your intellectual property rights have been violated, please provide us with the following information:
                        <br/><br/>
                        <div>
                            <ul>
                                <li>
                                    ●	a physical signature of the person authorized to act on behalf of the owner of the copyright or other intellectual property right;
                                </li>
                                <li>
                                    ●	a description of the copyrighted work or other intellectual property that you claim has been infringed or violated;
                                </li>
                                <li>
                                    ●	a description of where the material that you claim is infringing is located on our website;
                                </li>
                                <li>
                                    ●	your address, telephone number, and email address;
                                </li>
                                <li>
                                    ●	a statement by you that you have a good faith belief that the disputed use is not authorized by the copyright or intellectual property owner, its agent, or the law;
                                </li>
                                <li>
                                    ●	a statement by you, made under penalty of perjury, that the above information in your notice is accurate and that you are the copyright or intellectual property owner or authorized to act on the copyright or intellectual property owner's behalf.
                                </li>
                            </ul>
                        </div>
                        <br/>
                        Please send the foregoing information to our agent for notice of claims of copyright or other intellectual property infringement at:
                        <br/><br/>
                        Copyright Agent<br/>
                        Motors Confidence (M) Sdn Bhd (Import Car),<br/>
                        Lot 3470, Jalan SS 23/15, Taman Sea,<br/>
                        47300 Petaling Jaya, Selangor, Malaysia<br/>

                    </p>
                    <p>
                        <b>GENERAL INFORMATION</b><br/>
                        These Terms of Service may be amended from time to time without notice in Tanya-Je’s sole discretion. Any changes to the Terms of Service will be effective immediately upon the posting of the revised Terms of Service on this website. The Terms of Service, including any agreements and terms incorporated by reference herein, constitute the entire agreement between you and Tanya-Je and govern your use of the Services, including this website, and supersede any prior version of these Terms of Service between you and Tanya-Je with respect to the Services. You agree that no agency relationship is created between you and Tanya-Je as a result of these Terms of Service or your access to and/or use of the Services. The failure of Tanya-Je to exercise or enforce any right or provision of the Terms of Service shall not constitute a waiver of such right or provision. If any provision of the Terms of Service is found by a court of competent jurisdiction to be invalid, the parties nevertheless agree that the court should endeavor to give effect to the parties' intentions as reflected in the provision, and the other provisions of the Terms of Service remain in full force and effect. You agree that, except as otherwise expressly provided in these Terms of Service, there shall be no third-party beneficiaries to these Terms of Service. The headings in these Terms of Service are for convenience only and have no legal or contractual effect. As used in these Terms of Service, the word “including” is a term of enlargement meaning “including without limitation” and does not denote exclusivity, and the words “will,” “shall,” and “must” are deemed to be equivalent and denote a mandatory obligation or prohibition, as applicable. All definitions apply both to their singular and plural forms, as the context may require. Please report any violations of the Terms of Service to violations@tanyaje.com.my. Questions regarding these Terms of Service should be sent to legal@tanyaje.com.my.

                    </p>
                    <p>
                        The Tanya-Je entities do not make any representation or warranty that you will sell your vehicle, obtain an acceptable price for your vehicle, receive legitimate inquiries or solicitations from qualified buyers, or receive any inquiries regarding your vehicle for sale.
                    </p>
                    <p>
                        Last updated on and effective as of July 1, 2020.
                    </p>
                    <p>
                        Motors Confidence (M) Sdn Bhd (Import Car),<br/>
                        Lot 3470, Jalan SS 23/15, Taman Sea,<br/>
                        47300 Petaling Jaya, Selangor, Malaysia<br/>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- END aboutUsArea -->

</section>
<!-- END content_part-->

@endsection