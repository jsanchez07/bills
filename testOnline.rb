require 'watir'

browser = Watir::Browser.start("https://www.hrsaccount.com/hrs/ecare?cmd_Redirect=Home&acctOrg=169&logoCode=000")
browser.text_field(:id, "username").set("i3mj23")
browser.text_field(:id, "pass").set("hjsd2000")
browser.button(:id, /login/).click
browser.link(:id, /nav3/).click
browser.input(:id, /obp_payment/).click

