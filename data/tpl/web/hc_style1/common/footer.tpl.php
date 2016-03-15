<?php defined('IN_IA') or exit('Access Denied');?> 
        </section>
      </section>
      <aside class="bg-light lter b-l aside-md hide" id="notes">
        <div class="wrapper">Notification</div>
      </aside>
    </section>
  </section>
</section>
 
	<script>
		function subscribe(){
			$.post("<?php  echo url('utility/subscribe');?>", function(){
				setTimeout(subscribe, 5000);
			});
		}
		function sync() {
			$.post("<?php  echo url('utility/sync');?>", function(){
				setTimeout(sync, 60000);
			});
		}
		$(function(){
			subscribe();
			sync();
		});
	</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer-base', TEMPLATE_INCLUDEPATH)) : (include template('common/footer-base', TEMPLATE_INCLUDEPATH));?>