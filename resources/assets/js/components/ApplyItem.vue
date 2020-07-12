<template>
    <section>
        <button @click="toggle(true)" class="button highlighted-element highlighted-text" target="_blank" href="https://github.com/starcitizendotguide/Honest-Science/blob/master/CODE_OF_CONDUCT.md">Read Code of Conduct & Apply</button>

        <b-modal :active.sync="isActive">
            <div class="card">
                <div class="card-content">
                    <div class="content">
                        <h3>Contributor Covenant Code of Conduct</h3>

                        <h5>Our Pledge</h5>
                        <p>
                            In the interest of fostering an open and welcoming environment, we as contributors and maintainers pledge to making participation in our project and our community a harassment-free experience for everyone, regardless of age, body size, disability, ethnicity, gender identity and expression, level of experience, nationality, personal appearance, race, religion, or sexual identity and orientation.
                        </p>

                        <h5>Our Standards</h5>
                        <p>
                            Examples of behavior that contributes to creating a positive environment include:
                        </p>
                        <ul>
                            <li>Using welcoming and inclusive language</li>
                            <li>Being respectful of differing viewpoints and experiences</li>
                            <li>Gracefully accepting constructive criticism</li>
                            <li>Focusing on what is best for the community</li>
                            <li>Showing empathy towards other community members</li>
                        </ul>
                        <p>
                            Examples of unacceptable behavior by participants include:
                        </p>
                        <ul>
                            <li>The use of sexualized language or imagery and unwelcome sexual attention or advances</li>
                            <li>Trolling, insulting/derogatory comments, and personal or political attacks</li>
                            <li>Public or private harassment</li>
                            <li>Publishing others' private information, such as a physical or electronic address, without explicit permission</li>
                            <li>Other conduct which could reasonably be considered inappropriate in a professional setting</li>
                        </ul>

                        <h5>Our Responsibilities</h5>
                        <p>
                            Project maintainers are responsible for clarifying the standards of acceptable behavior and are expected to take appropriate and fair corrective action in response to any instances of unacceptable behavior.
                        </p>
                        <p>
                            Project maintainers have the right and responsibility to remove, edit, or reject comments, commits, code, wiki edits, issues, and other contributions that are not aligned to this Code of Conduct, or to ban temporarily or permanently any contributor for other behaviors that they deem inappropriate, threatening, offensive, or harmful.
                        </p>

                        <h5>Scope</h5>
                        <p>
                            This Code of Conduct applies both within project spaces and in public spaces when an individual is representing the project or its community. Examples of representing a project or community include using an official project e-mail address, posting via an official social media account, or acting as an appointed representative at an online or offline event. Representation of a project may be further defined and clarified by project maintainers.
                        </p>

                        <h5>Enforcement</h5>
                        <p>
                            Instances of abusive, harassing, or otherwise unacceptable behavior may be reported by contacting the project team at <b><i>projects -at- krueger -minus- jan -dot- de</i></b>. The project team will review and investigate all complaints, and will respond in a way that it deems appropriate to the circumstances. The project team is obligated to maintain confidentiality with regard to the reporter of an incident. Further details of specific enforcement policies may be posted separately.
                        </p>
                        <p>
                            Project maintainers who do not follow or enforce the Code of Conduct in good faith may face temporary or permanent repercussions as determined by other members of the project's leadership.
                        </p>

                        <h5>Attribution</h5>
                        This Code of Conduct is adapted from the <a href="http://contributor-covenant.org" target="_blank">Contributor Covenant</a>, version 1.4, available at <a href="http://contributor-covenant.org/version/1/4" target="_blank">http://contributor-covenant.org/version/1/4</a>
                    </div>
                    <div>
                        <b-tooltip
                                label="Please read the Code of Conduct and make sure you understand all of it before you accept it."
                                :active="countdown > 0"
                                multilined
                                type="is-info"
                                position="is-right">
                            <a :href="acceptLink" class="button is-success has-text-black" :disabled="countdown > 0" v-text="buttonAcceptText"></a>
                        </b-tooltip>
                        <button @click="toggle(false)" class="button is-danger">Cancel</button>
                    </div>
                </div>
            </div>
        </b-modal>
    </section>
</template>

<script type="text/javascript">
export default {
    data: function() {
        return {
            isActive: false,
            isAcceptAvailable: false,
            countdown: null,
            buttonAcceptText: 'Accept',
            acceptLink: '#',
            countdownId: null,
        };
    },
    methods: {
        activateCountdown() {
            var self = this;

            if(!(this.countdownId === null)) {
                clearInterval(this.countdownId);
            }

            this.countdownId = setInterval(() => {
                self.buttonAcceptText = ('Accept & Apply (' + self.countdown + 's)');
                self.countdown--;

                if(self.countdown <= 0) {
                    self.buttonAcceptText = ('Accept & Apply');
                    //TODO replace link
                    self.acceptLink = 'https://docs.google.com/forms/d/e/1FAIpQLSdqL3CZrA6CIWeX_fIwWfRfAo0zENx-5ceiqFFoXoeowqEXUA/viewform?usp=sf_link';
                    clearInterval(self.countdownId);
                }
            }, 1000);
        },
        toggle(value) {
            if(value === true) {
                this.countdown = 30;
                this.activateCountdown();
                this.isActive = true;
            } else {
                this.isActive = false;
            }
        }
    }
}
</script>
